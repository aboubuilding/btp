<?php
namespace App\Http\Requests\Socle;

use App\Domain\Socle\Models\Parametre;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParametreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Parametre::class);
    }

    public function rules(): array
    {
        $parametreId = $this->route('parametre')?->id;

        return [
            'cle'         => ['required', 'string', 'max:100', Rule::unique('parametres', 'cle')->ignore($parametreId)],
            'valeur'      => ['required', 'string'],
            'type'        => ['required', Rule::in(['string','decimal','integer','boolean','json'])],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function after(): array
    {
        return [function ($validator) {
            $type = $this->input('type');
            $valeur = $this->input('valeur');

            $valide = match ($type) {
                'integer' => is_numeric($valeur) && (int) $valeur == $valeur,
                'decimal' => is_numeric($valeur),
                'boolean' => in_array($valeur, ['0', '1', 'true', 'false'], true),
                'json'    => json_decode($valeur) !== null,
                default   => true,
            };

            if (!$valide) {
                $validator->errors()->add('valeur', "La valeur ne correspond pas au type « {$type} ».");
            }
        }];
    }
}