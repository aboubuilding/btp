<?php
namespace App\Http\Requests\Approvisionnement;

use Illuminate\Foundation\Http\FormRequest;

class RejeterDemandeAchatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('directeur_technique', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'motif' => ['required', 'string', 'max:500'],
        ];
    }
}