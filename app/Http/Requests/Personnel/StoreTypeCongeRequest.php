<?php
namespace App\Http\Requests\Personnel;

use App\Domain\Personnel\Models\TypeConge;
use Illuminate\Foundation\Http\FormRequest;

class StoreTypeCongeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', TypeConge::class);
    }

    public function rules(): array
    {
        return [
            'nom'              => ['required', 'string', 'max:100'],
            'jours_par_defaut' => ['nullable', 'integer', 'min:0', 'max:365'],
        ];
    }
}