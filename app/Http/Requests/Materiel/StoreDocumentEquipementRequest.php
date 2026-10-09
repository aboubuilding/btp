<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentEquipementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'admin');
    }

    public function rules(): array
    {
        return [
            'type'            => ['required', Rule::in(['assurance', 'visite_technique', 'carte_grise', 'autre'])],
            'numero'          => ['nullable', 'string', 'max:100'],
            'date_emission'   => ['nullable', 'date'],
            'date_expiration' => ['nullable', 'date', 'after:date_emission'],
            'chemin_fichier'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}