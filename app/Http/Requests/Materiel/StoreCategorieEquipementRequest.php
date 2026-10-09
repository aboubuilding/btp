<?php
namespace App\Http\Requests\Materiel;

use App\Domain\ParcMateriel\Models\CategorieEquipement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategorieEquipementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CategorieEquipement::class);
    }

    public function rules(): array
    {
        $categorieId = $this->route('categorie')?->id;

        return [
            'nom' => ['required', 'string', 'max:100', Rule::unique('categorie_equipements', 'nom')->ignore($categorieId)],
        ];
    }
}