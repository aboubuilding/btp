<?php
namespace App\Http\Requests\Approvisionnement;

use Illuminate\Foundation\Http\FormRequest;

class ValiderDemandeAchatRequest extends FormRequest
{
    public function authorize(): bool
    {
        $demande = $this->route('demande');
        return $this->user()->hasRole('directeur_technique', 'direction', 'admin')
            && $demande->statut === 'en_attente';
    }

    public function rules(): array
    {
        return [];
    }
}