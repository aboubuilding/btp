<?php
namespace App\Http\Requests\Approvisionnement;

use App\Domain\Approvisionnement\Models\Fournisseur;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFournisseurRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Fournisseur::class);
    }

    public function rules(): array
    {
        return [
            'nom'             => ['required', 'string', 'max:255'],
            'contact'         => ['nullable', 'string', 'max:150'],
            'telephone'       => ['nullable', 'string', 'max:30'],
            'email'           => ['nullable', 'email', 'max:150'],
            'adresse'         => ['nullable', 'string', 'max:255'],
            'categorie'       => ['nullable', Rule::in(['materiaux', 'carburant', 'pieces_detachees', 'location_engins', 'services', 'divers'])],
            'note_evaluation' => ['nullable', 'numeric', 'between:0,5'],
        ];
    }
}