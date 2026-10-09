<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;

class SignerAvenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_signature' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}