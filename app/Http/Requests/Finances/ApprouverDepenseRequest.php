<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverDepenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'direction', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [];
    }
}