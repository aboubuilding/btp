<?php
namespace App\Http\Requests\Approvisionnement;

use App\Domain\Socle\Services\ParametreService;
use Illuminate\Foundation\Http\FormRequest;

class ValiderBonCommandeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('direction', 'admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $bc = $this->route('bon_commande');
            $seuil = app(ParametreService::class)->getSeuilValidationDG('bc');

            if ((float) $bc->montant_total < $seuil) {
                $v->errors()->add('montant', "Ce BC ne nécessite pas de validation DG (seuil : {$seuil} FCFA).");
            }
        });
    }
}