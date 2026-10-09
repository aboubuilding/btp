<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\Inventaire;
use Illuminate\Foundation\Http\FormRequest;

class StoreInventaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Inventaire::class);
    }

    public function rules(): array
    {
        return [
            'entrepot_id'     => ['required', 'exists:entrepots,id'],
            'date_inventaire' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}