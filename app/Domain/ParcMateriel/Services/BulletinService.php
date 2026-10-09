<?php
namespace App\Domain\Personnel\Services;

use App\Domain\Personnel\Models\BulletinPaie;
use App\Domain\Personnel\Repositories\BulletinPaieRepositoryInterface;
use App\Domain\Socle\Exceptions\RegleGestionException;
use App\Domain\Socle\Services\{JournalService, PdfService};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BulletinService
{
    public function __construct(
        private BulletinPaieRepositoryInterface $repo,
        private JournalService $journal,
        private PdfService $pdf,
    ) {}

    public function paginate(array $filtres = [], int $parPage = 25): LengthAwarePaginator
    {
        return $this->repo->paginateAvecRelations($filtres, $parPage);
    }

    public function valider(BulletinPaie $bulletin): BulletinPaie
    {
        if ($bulletin->statut !== 'brouillon') {
            throw new RegleGestionException('Bulletin déjà traité.');
        }
        $bulletin = $this->repo->update($bulletin, ['statut' => 'valide']);
        $this->journal->log('bulletin.valide', $bulletin);
        return $bulletin;
    }

    public function marquerPaye(BulletinPaie $bulletin): BulletinPaie
    {
        $bulletin = $this->repo->update($bulletin, ['statut' => 'paye']);
        $this->journal->log('bulletin.paye', $bulletin);
        return $bulletin;
    }

    public function genererPdf(BulletinPaie $bulletin)
    {
        $bulletin->load(['employe.poste', 'periode']);

        return $this->pdf->download(
            'pdf.bulletin',
            ['bulletin' => $bulletin],
            "BP-{$bulletin->employe->matricule}-{$bulletin->periode->id}"
        );
    }

    public function statistiques(?int $periodeId = null): array
    {
        return $this->repo->statistiques($periodeId);
    }
}