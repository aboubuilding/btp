<?php
namespace App\Http\Requests\Personnel;

use App\Domain\Personnel\Models\Departement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Departement::class);
    }

    public function rules(): array
    {
        $departementId = $this->route('departement')?->id;

        return [
            'nom' => ['required', 'string', 'max:100', Rule::unique('departements', 'nom')->ignore($departementId)],
        ];
    }
}