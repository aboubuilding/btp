<?php
namespace App\Http\Requests\Paie;

use App\Domain\Personnel\Models\PeriodePaie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePeriodePaieRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', PeriodePaie::class);
    }

    public function rules(): array
    {
        return [
            'libelle'    => ['required', 'string', 'max:100'],
            'type'       => ['required', Rule::in(['hebdomadaire', 'mensuelle'])],
            'date_debut' => ['required', 'date'],
            'date_fin'   => ['required', 'date', 'after:date_debut'],
        ];
    }
}