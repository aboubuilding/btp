<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;

class SignerPvReceptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('directeur_technique', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [function ($validator) {
            $pv = $this->route('pv');

            if ($pv->type === 'definitive') {
                $pvProvisoire = \App\Domain\QHSE\Models\PvReception::where('projet_id', $pv->projet_id)
                    ->where('type', 'provisoire')
                    ->first();

                if ($pvProvisoire && !$pvProvisoire->toutes_reserves_levees) {
                    $validator->errors()->add('pv', 'Toutes les réserves doivent être levées (RG-Q02).');
                }
            }
        }];
    }
}