<?php
namespace App\Http\Requests\Personnel;

use App\Domain\Personnel\Models\DemandeConge;
use Illuminate\Foundation\Http\FormRequest;

class StoreCongeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DemandeConge::class);
    }

    public function rules(): array
    {
        return [
            'employee_id'   => ['required', 'exists:employees,id'],
            'type_conge_id' => ['required', 'exists:type_conges,id'],
            'date_debut'    => ['required', 'date', 'after_or_equal:today'],
            'date_fin'      => ['required', 'date', 'after_or_equal:date_debut'],
            'nombre_jours'  => ['required', 'integer', 'min:1', 'max:60'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $debut = \Carbon\Carbon::parse($this->date_debut);
            $fin = \Carbon\Carbon::parse($this->date_fin);
            $joursReels = $debut->diffInDays($fin) + 1;

            if ($this->nombre_jours != $joursReels) {
                $validator->errors()->add('nombre_jours', "Le nombre de jours ({$this->nombre_jours}) ne correspond pas à la période ({$joursReels}).");
            }
        }];
    }
}