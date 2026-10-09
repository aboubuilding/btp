<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;

class SignerMarcheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('signer', $this->route('marche'));
    }

    public function rules(): array
    {
        return [
            'date_signature' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}