<?php
namespace App\Http\Controllers;

use App\Domain\Execution\Models\Projet;
use App\Domain\Commercial\Models\Client;
use App\Domain\Personnel\Models\Employe;
use App\Domain\Finances\Models\Facture;
use App\Domain\Approvisionnement\Models\Materiau;
use App\Domain\ParcMateriel\Models\Equipement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));
        if (mb_strlen($q) < 2) return response()->json([]);

        $like = "%{$q}%";
        $results = collect();

        // Chantiers
        foreach (Projet::where('nom', 'like', $like)->orWhere('code', 'like', $like)->limit(5)->get() as $p) {
            $results->push([
                'title'    => "{$p->code} — {$p->nom}",
                'subtitle' => "Chantier · " . ($p->client->nom ?? ''),
                'icon'     => 'fa-diagram-project',
                'category' => 'Chantier',
                'url'      => route('projets.show', $p),
            ]);
        }

        // Clients
        foreach (Client::where('nom', 'like', $like)->limit(5)->get() as $c) {
            $results->push([
                'title'    => $c->nom,
                'subtitle' => "Client · {$c->type_label}",
                'icon'     => 'fa-user',
                'category' => 'Client',
                'url'      => route('commercial.clients.show', $c),
            ]);
        }

        // Employés
        foreach (Employe::where('nom', 'like', $like)
            ->orWhere('prenom', 'like', $like)
            ->orWhere('matricule', 'like', $like)
            ->limit(5)->get() as $e) {
            $results->push([
                'title'    => $e->nom_complet,
                'subtitle' => "{$e->matricule} · " . ($e->poste?->nom ?? ''),
                'icon'     => 'fa-id-badge',
                'category' => 'Employé',
                'url'      => route('rh.employes.show', $e),
            ]);
        }

        // Factures
        foreach (Facture::where('numero_facture', 'like', $like)->limit(5)->get() as $f) {
            $results->push([
                'title'    => $f->numero_facture,
                'subtitle' => "Facture · " . number_format($f->montant_ttc, 0, ',', ' ') . ' FCFA',
                'icon'     => 'fa-file-invoice-dollar',
                'category' => 'Facture',
                'url'      => route('finances.factures.show', $f),
            ]);
        }

        // Matériaux
        foreach (Materiau::where('nom', 'like', $like)->orWhere('code', 'like', $like)->limit(5)->get() as $m) {
            $results->push([
                'title'    => $m->nom,
                'subtitle' => "{$m->code} · Stock : {$m->quantite_totale} {$m->unite}",
                'icon'     => 'fa-cubes',
                'category' => 'Matériau',
                'url'      => route('logistique.materiaux.index'),
            ]);
        }

        // Équipements
        foreach (Equipement::where('nom', 'like', $like)->orWhere('code', 'like', $like)->limit(5)->get() as $eq) {
            $results->push([
                'title'    => $eq->nom,
                'subtitle' => "{$eq->code} · {$eq->statut_label}",
                'icon'     => 'fa-truck',
                'category' => 'Équipement',
                'url'      => route('materiel.equipements.show', $eq),
            ]);
        }

        return response()->json($results);
    }
}