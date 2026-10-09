<?php
namespace App\Http\Requests\Paie;

use Illuminate\Foundation\Http\FormRequest;

class GenererBulletinsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [function ($validator) {
            $periode = $this->route('periode');
            if ($periode->statut !== 'ouverte') {
                $validator->errors()->add('statut', 'Période déjà clôturée.');
            }
        }];
    }
}