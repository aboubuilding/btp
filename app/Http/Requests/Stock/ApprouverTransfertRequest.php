<?php
namespace App\Http\Requests\Stock;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverTransfertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_achat', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [];
    }
}