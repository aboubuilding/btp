<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDependanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('tache')->projet);
    }

    public function rules(): array
    {
        return [
            'depend_de_tache_id' => ['required', 'exists:taches,id', 'different:tache_id'],
            'type'               => ['required', Rule::in(['fin_debut', 'debut_debut', 'fin_fin', 'debut_fin'])],
        ];
    }
}