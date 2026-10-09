<?php
namespace App\Http\Requests\Paie;

use App\Domain\Personnel\Models\AvanceSalaire;
use Illuminate\Foundation\Http\FormRequest;

class StoreAvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', AvanceSalaire::class);
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'montant'     => ['required', 'numeric', 'gt:0', 'max:5000000'],
            'date_avance' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}