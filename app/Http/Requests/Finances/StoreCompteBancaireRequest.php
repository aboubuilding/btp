<?php
namespace App\Http\Requests\Finances;

use App\Domain\Finances\Models\CompteBancaire;
use Illuminate\Foundation\Http\FormRequest;

class StoreCompteBancaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CompteBancaire::class);
    }

    public function rules(): array
    {
        return [
            'libelle'       => ['required', 'string', 'max:100'],
            'banque'        => ['required', 'string', 'max:100'],
            'numero'        => ['required', 'string', 'max:50'],
            'solde_initial' => ['nullable', 'numeric'],
        ];
    }
}