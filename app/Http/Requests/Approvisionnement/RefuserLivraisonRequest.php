<?php
namespace App\Http\Requests\Approvisionnement;

use Illuminate\Foundation\Http\FormRequest;

class RefuserLivraisonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('magasinier', 'responsable_achat', 'admin');
    }

    public function rules(): array
    {
        return [
            'motif' => ['required', 'string', 'max:500'],
        ];
    }
}