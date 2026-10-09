<?php
namespace App\Http\Requests\Personnel;

use App\Domain\Personnel\Models\Poste;
use Illuminate\Foundation\Http\FormRequest;

class StorePosteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Poste::class);
    }

    public function rules(): array
    {
        return [
            'nom'            => ['required', 'string', 'max:100'],
            'departement_id' => ['nullable', 'exists:departements,id'],
        ];
    }
}