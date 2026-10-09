<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;

class StorePhaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        $projet = $this->route('projet') ?? $this->route('phase')->projet;
        return $this->user()->can('update', $projet);
    }

    public function rules(): array
    {
        return [
            'nom'        => ['required', 'string', 'max:255'],
            'ordre'      => ['nullable', 'integer', 'min:0', 'max:100'],
            'date_debut' => ['nullable', 'date'],
            'date_fin'   => ['nullable', 'date', 'after_or_equal:date_debut'],
        ];
    }
}