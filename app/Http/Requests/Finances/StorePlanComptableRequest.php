<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanComptableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'admin');
    }

    public function rules(): array
    {
        $compteId = $this->route('plan_comptable')?->id;

        return [
            'numero'    => ['required', 'string', 'max:20', Rule::unique('plan_comptables', 'numero')->ignore($compteId)],
            'libelle'   => ['required', 'string', 'max:255'],
            'classe'    => ['required', Rule::in(['1','2','3','4','5','6','7','8','9'])],
            'parent_id' => ['nullable', 'exists:plan_comptables,id'],
        ];
    }
}