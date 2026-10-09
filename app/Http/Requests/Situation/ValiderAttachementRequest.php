<?php
namespace App\Http\Requests\Situation;

use Illuminate\Foundation\Http\FormRequest;

class ValiderAttachementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('conducteur_travaux', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [];
    }
}