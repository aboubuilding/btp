<?php
namespace App\Http\Requests\Execution;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvancementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('projet'));
    }

    public function rules(): array
    {
        return [
            'date_rapport'           => ['required', 'date', 'before_or_equal:today'],
            'meteo'                  => ['nullable', 'string', 'max:50'],
            'ouvriers_presents'      => ['nullable', 'integer', 'min:0', 'max:500'],
            'materiel_present'       => ['nullable', 'string', 'max:2000'],
            'livraisons_recues'      => ['nullable', 'string', 'max:2000'],
            'travaux_realises'       => ['nullable', 'string', 'max:5000'],
            'incidents'              => ['nullable', 'string', 'max:2000'],
            'difficultes'            => ['nullable', 'string', 'max:2000'],
            'pourcentage_avancement' => ['required', 'integer', 'between:0,100'],
        ];
    }
}