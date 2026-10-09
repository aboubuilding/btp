<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;

class LettragePaiementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'admin');
    }

    public function rules(): array
    {
        return [
            'facture_id' => ['required', 'exists:factures,id'],
        ];
    }
}