<?php
namespace App\Domain\SousTraitance\Services;

use App\Domain\SousTraitance\Models\{EvaluationSousTraitant, Soustraitant};
use App\Domain\Socle\Services\JournalService;

class EvaluationService
{
    public function __construct(private JournalService $journal) {}

    public function creer(Soustraitant $soustraitant, array $data, ?int $userId = null): EvaluationSousTraitant
    {
        $data['sous_traitant_id'] = $soustraitant->id;
        $data['evalue_par'] = $userId ?? auth()->id();
        $data['date_evaluation'] = $data['date_evaluation'] ?? now();

        $evaluation = $soustraitant->evaluations()->create($data);
        $this->journal->log('evaluation_st.creee', $evaluation);

        return $evaluation;
    }

    public function noteMoyenne(Soustraitant $soustraitant): ?float
    {
        return $soustraitant->note_moyenne;
    }
}