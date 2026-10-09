<?php
namespace App\Http\Controllers\Finances;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finances\{StoreFactureRequest, UpdateFactureRequest};
use App\Domain\Finances\Models\Facture;
use App\Domain\Finances\Services\FacturationService;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Http\Request;

class FactureController extends Controller
{
    public function __construct(private FacturationService $service) {}

    public function index(Request $request)
    {
        $factures = $this->service->paginate($request->only(['search', 'type', 'statut', 'projet_id']));
        $stats = $this->service->statistiques();
        return view('finances.factures.index', compact('factures', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Facture::class);
        return view('finances.factures.create');
    }

    public function store(StoreFactureRequest $request)
    {
        $facture = $this->service->creer($request->validated());
        return redirect()->route('finances.factures.show', $facture)->with('success', 'Facture créée.');
    }

    public function show(Facture $facture)
    {
        $this->authorize('view', $facture);
        $facture = $this->service->avecDetails($facture);
        return view('finances.factures.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        $this->authorize('update', $facture);
        return view('finances.factures.edit', compact('facture'));
    }

    public function update(UpdateFactureRequest $request, Facture $facture)
    {
        $facture->update($request->validated());
        return redirect()->route('finances.factures.show', $facture)->with('success', 'Facture mise à jour.');
    }

    public function pdf(Facture $facture, PdfService $pdf)
    {
        $this->authorize('pdf', $facture);
        $facture = $this->service->avecDetails($facture);
        return $pdf->download('pdf.facture', ['facture' => $facture], $facture->numero_facture);
    }

    public function preview(Facture $facture, PdfService $pdf)
    {
        $this->authorize('pdf', $facture);
        $facture = $this->service->avecDetails($facture);
        return $pdf->stream('pdf.facture', ['facture' => $facture], $facture->numero_facture);
    }

    public function annuler(Facture $facture)
    {
        $this->authorize('annuler', $facture);
        $facture->update(['statut' => 'annulee']);
        return back()->with('success', 'Facture annulée.');
    }
}