<?php
namespace App\Http\Requests\Paie;

use App\Domain\Execution\Models\{Projet, EquipeProjet};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\{Rule, Validator};

class StorePresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        $projet = Projet::findOrFail($this->input('projet_id'));
        return $this->user()->can('pointer', $projet);
    }

    public function rules(): array
    {
        return [
            'projet_id' => ['required', 'exists:projets,id'],
            'date'      => ['required', 'date', 'before_or_equal:today'],
            'lignes'    => ['required', 'array', 'min:1'],
            'lignes.*.employee_id'   => ['required', 'distinct', 'exists:employees,id'],
            'lignes.*.statut'        => ['required', Rule::in(['present', 'absent', 'retard', 'conge', 'maladie', 'ferie'])],
            'lignes.*.heure_arrivee' => ['nullable', 'date_format:H:i'],
            'lignes.*.heure_depart'  => ['nullable', 'date_format:H:i', 'after:lignes.*.heure_arrivee'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator) {
            $equipeIds = EquipeProjet::where('projet_id', $this->projet_id)
                ->pluck('employee_id')
                ->all();

            foreach ($this->input('lignes', []) as $i => $ligne) {
                if (!in_array($ligne['employee_id'], $equipeIds, true)) {
                    $validator->errors()->add(
                        "lignes.{$i}.employee_id",
                        'Cet employé n\'est pas affecté à ce chantier (RG-P01).'
                    );
                }
            }
        }];
    }
}