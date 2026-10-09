<?php
namespace App\Http\Requests\Finances;

use Illuminate\Foundation\Http\FormRequest;

class CloturerExerciceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('comptable', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [];
    }

    public function after(): array
    {
        return [function ($validator) {
            $exercice = $this->route('exercice');
            $brouillons = $exercice->ecritures()->where('statut', 'brouillon')->count();

            if ($brouillons > 0) {
                $validator->errors()->add(
                    'exercice',
                    "Impossible de clôturer : {$brouillons} écriture(s) en brouillon."
                );
            }
        }];
    }
}