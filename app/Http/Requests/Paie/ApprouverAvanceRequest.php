<?php
namespace App\Http\Requests\Paie;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverAvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [];
    }
}