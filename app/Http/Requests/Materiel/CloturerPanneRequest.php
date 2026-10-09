<?php
namespace App\Http\Requests\Materiel;

use Illuminate\Foundation\Http\FormRequest;

class CloturerPanneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_materiel', 'directeur_technique', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_cloture'    => ['required', 'date', 'after_or_equal:date_panne'],
            'cout_reparation' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}