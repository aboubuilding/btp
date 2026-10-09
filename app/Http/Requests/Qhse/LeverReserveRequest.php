<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;

class LeverReserveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'conducteur_travaux', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_levee'   => ['required', 'date', 'before_or_equal:today'],
            'commentaire'  => ['nullable', 'string', 'max:1000'],
        ];
    }
}