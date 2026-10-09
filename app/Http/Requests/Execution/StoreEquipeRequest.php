<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEquipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('projet'));
    }

    public function rules(): array
    {
        return [
            'employee_id'   => ['required', 'exists:employees,id'],
            'role_chantier' => ['required', Rule::in(['chef_equipe', 'ouvrier', 'pointeur', 'topographe', 'autre'])],
            'date_debut'    => ['required', 'date'],
            'date_fin'      => ['nullable', 'date', 'after_or_equal:date_debut'],
        ];
    }
}