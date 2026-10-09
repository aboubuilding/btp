<?php
namespace App\Http\Requests\Approvisionnement;

use App\Domain\Approvisionnement\Models\DemandeAchat;
use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeAchatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', DemandeAchat::class);
    }

    public function rules(): array
    {
        return [
            'numero'                 => ['required', 'string', 'max:50'],
            'projet_id'              => ['required', 'exists:projets,id'],
            'demandeur_id'           => ['required', 'exists:employees,id'],
            'date_demande'           => ['required', 'date', 'before_or_equal:today'],
            'articles'               => ['required', 'array', 'min:1', 'max:100'],
            'articles.*.designation' => ['required', 'string', 'max:255'],
            'articles.*.quantite'    => ['required', 'numeric', 'gt:0'],
            'articles.*.unite'       => ['required', 'string', 'max:20'],
        ];
    }
}