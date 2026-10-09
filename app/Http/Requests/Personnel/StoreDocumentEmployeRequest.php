<?php
namespace App\Http\Requests\Personnel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDocumentEmployeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'admin');
    }

    public function rules(): array
    {
        return [
            'type'            => ['required', Rule::in(['diplome', 'habilitation', 'certificat_medical', 'autre'])],
            'nom'             => ['required', 'string', 'max:255'],
            'chemin_fichier'  => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'date_expiration' => ['nullable', 'date', 'after:today'],
        ];
    }
}