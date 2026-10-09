<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Domain\Personnel\Models\BulletinPaie;
use App\Domain\Personnel\Services\BulletinService;
use App\Domain\Socle\Services\PdfService;
use Illuminate\Http\Request;

class BulletinPaieController extends Controller
{
    public function __construct(private BulletinService $service) {}

    public function index(Request $request)
    {
        $bulletins = $this->service->paginate($request->only(['periode_paie_id', 'employee_id', 'statut']));
        return view('rh.bulletins.index', compact('bulletins'));
    }

    public function show(BulletinPaie $bulletin)
    {
        $this->authorize('view', $bulletin);
        $bulletin->load(['employe.poste', 'periode']);
        return view('rh.bulletins.show', compact('bulletin'));
    }

    public function valider(BulletinPaie $bulletin)
    {
        $this->authorize('valider', $bulletin);
        $this->service->valider($bulletin);
        return back()->with('success', 'Bulletin validé.');
    }

    public function marquerPaye(BulletinPaie $bulletin)
    {
        $this->authorize('marquerPaye', $bulletin);
        $this->service->marquerPaye($bulletin);
        return back()->with('success', 'Bulletin marqué payé.');
    }

    public function pdf(BulletinPaie $bulletin, PdfService $pdf)
    {
        $this->authorize('pdf', $bulletin);
        $bulletin->load(['employe.poste', 'periode']);
        return $pdf->download('pdf.bulletin', ['bulletin' => $bulletin], "BP-{$bulletin->employe->matricule}-{$bulletin->periode->id}");
    }
}