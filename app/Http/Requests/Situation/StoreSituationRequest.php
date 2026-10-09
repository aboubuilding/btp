<?php
namespace App\Http\Requests\Situation;

use App\Domain\Execution\Models\Situation;
use Illuminate\Foundation\Http\FormRequest;

class StoreSituationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Situation::class);
    }

    public function rules(): array
    {
        return [
            'projet_id'      => ['required', 'exists:projets,id'],
            'attachement_id' => ['required', 'exists:attachements,id'],
            'periode_debut'  => ['required', 'date'],
            'periode_fin'    => ['required', 'date', 'after_or_equal:periode_debut'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $attachement = \App\Domain\Execution\Models\Attachement::find($this->attachement_id);
            if ($attachement && $attachement->statut !== 'valide') {
                $validator->errors()->add('attachement_id', 'L\'attachement doit être validé.');
            }
            if ($attachement && $attachement->projet_id != $this->projet_id) {
                $validator->errors()->add('attachement_id', 'Attachement d\'un autre chantier.');
            }
        }];
    }
}