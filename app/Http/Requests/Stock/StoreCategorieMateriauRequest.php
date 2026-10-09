<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\CategorieMateriau;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategorieMateriauRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CategorieMateriau::class);
    }

    public function rules(): array
    {
        $categorieId = $this->route('categorie')?->id;

        return [
            'nom' => ['required', 'string', 'max:100', Rule::unique('categorie_materiaux', 'nom')->ignore($categorieId)],
        ];
    }
}