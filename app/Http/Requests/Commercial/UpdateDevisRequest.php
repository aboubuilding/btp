<?php
namespace App\Http\Requests\Commercial;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDevisRequest extends StoreDevisRequest
{
    public function authorize(): bool
    {
        $devis = $this->route('devis');
        return $this->user()->can('update', $devis)
            && $devis->statut !== 'accepte';
    }

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'numero' => ['required', 'string', 'max:50'],
        ]);
    }
}