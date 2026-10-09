<?php
namespace App\Http\Requests\Situation;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverSituationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_approbation' => ['nullable', 'date', 'before_or_equal:today'],
            'reference_client' => ['nullable', 'string', 'max:100'],
        ];
    }
}