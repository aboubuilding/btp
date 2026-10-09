<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;

class AlimenterCaisseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'montant' => ['required', 'numeric', 'min:1'],
        ];
    }
}