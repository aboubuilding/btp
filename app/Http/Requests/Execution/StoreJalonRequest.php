<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;

class StoreJalonRequest extends FormRequest
{
    public function authorize(): bool
    {
        $projet = $this->route('projet') ?? $this->route('jalon')->projet;
        return $this->user()->can('update', $projet);
    }

    public function rules(): array
    {
        return [
            'libelle'       => ['required', 'string', 'max:255'],
            'date_echeance' => ['required', 'date'],
            'date_atteinte' => ['nullable', 'date', 'after_or_equal:date_echeance'],
        ];
    }
}