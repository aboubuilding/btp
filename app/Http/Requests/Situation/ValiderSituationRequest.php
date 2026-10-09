<?php
namespace App\Http\Requests\Situation;

use Illuminate\Foundation\Http\FormRequest;

class ValiderSituationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('valider', $this->route('situation'));
    }

    public function rules(): array
    {
        return [];
    }
}