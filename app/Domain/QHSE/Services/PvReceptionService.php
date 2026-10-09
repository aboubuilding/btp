<?php
namespace App\Domain\QHSE\Services;

use App\Domain\QHSE\Models\{PvReception, Reserve};
use App\Domain\QHSE\Repositories\PvReceptionRepositoryInterface;
use App\Domain\QHSE\Events\ReceptionDefinitiveSignee;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PvReceptionService
{
    public function __construct(
        private PvReceptionRepositoryInterface $repo,
        private JournalService $journal,
    ) {}

    public function creer(array $data, ?UploadedFile $fichier = null): PvReception
    {
        if ($fichier) {
            $data['chemin_fichier'] = $fichier->store('pv', 'local');
        }
        $data['statut'] = 'brouillon';

        $pv = $this->repo->create($data);
        $this->journal->log('pv.cree', $pv);
        return $pv;
    }

    public function ajouterReserve(PvReception $pv, array $data): Reserve
    {
        if ($pv->statut === 'signe') {
            throw new RegleGestionException('Impossible d\'ajouter une réserve à un PV signé.');
        }

        return DB::transaction(function () use ($pv, $data) {
            $reserve = $pv->reserves()->create(array_merge($data, ['statut' => 'ouverte']));

            if (!$pv->avec_reserves) {
                $pv->update(['avec_reserves' => true]);
            }

            $this->journal->log('reserve.ajoutee', $reserve);
            return $reserve;
        });
    }

    /**
     * RG-Q02 : PV définitif impossible tant qu'une réserve est ouverte.
     */
    public function signer(PvReception $pv, int $userId): PvReception
    {
        if ($pv->statut === 'signe') {
            throw new RegleGestionException('PV déjà signé.');
        }

        if ($pv->type === 'definitive') {
            $pvProvisoire = PvReception::where('projet_id', $pv->projet_id)
                ->where('type', 'provisoire')
                ->first();

            if ($pvProvisoire && !$pvProvisoire->toutes_reserves_levees) {
                throw new RegleGestionException(
                    'Toutes les réserves doivent être levées avant la réception définitive (RG-Q02).'
                );
            }
        }

        return DB::transaction(function () use ($pv) {
            $pv = $this->repo->update($pv, ['statut' => 'signe']);

            if ($pv->type === 'definitive') {
                event(new ReceptionDefinitiveSignee($pv));
            }

            $this->journal->log('pv.signe', $pv);
            return $pv;
        });
    }

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function avecDetails(PvReception $pv): PvReception
    {
        return $this->repo->avecDetails($pv);
    }

    public function statistiques(): array
    {
        return $this->repo->statistiques();
    }
}