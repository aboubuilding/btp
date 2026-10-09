<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\Entrepot;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEntrepotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Entrepot::class);
    }

    public function rules(): array
    {
        $entrepotId = $this->route('entrepot')?->id;

        return [
            'code'      => ['required', 'string', 'max:30', Rule::unique('entrepots', 'code')->ignore($entrepotId)],
            'nom'       => ['required', 'string', 'max:150'],
            'projet_id' => ['nullable', 'exists:projets,id'],
            'adresse'   => ['nullable', 'string', 'max:255'],
        ];
    }
}