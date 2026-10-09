<?php
namespace App\Http\Controllers\Socle;

use App\Http\Controllers\Controller;
use App\Domain\Socle\Services\JournalService;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function __construct(private JournalService $journal) {}

    public function index(Request $request)
    {
        $logs = $this->journal->paginateRecents($request->only(['search', 'action', 'user_id', 'date_debut', 'date_fin']));
        return view('admin.journal.index', compact('logs'));
    }

    public function pourObjet(string $type, int $id)
    {
        $logs = $this->journal->pourObjet($type, $id);
        return view('admin.journal.partials._liste', compact('logs'));
    }
}