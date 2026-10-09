<?php
namespace App\Http\Requests\Paie;

use Illuminate\Foundation\Http\FormRequest;

class ValiderPresenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('conducteur_travaux', 'directeur_technique', 'rh', 'admin');
    }

    public function rules(): array
    {
        return [
            'projet_id' => ['required', 'exists:projets,id'],
            'date'      => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}