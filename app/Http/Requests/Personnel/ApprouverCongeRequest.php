<?php
namespace App\Http\Requests\Personnel;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverCongeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'direction', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'commentaire' => ['nullable', 'string', 'max:500'],
        ];
    }
}