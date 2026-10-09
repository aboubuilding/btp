<?php
namespace App\Http\Requests\Stock;

use App\Domain\Approvisionnement\Models\TransfertStock;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransfertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', TransfertStock::class);
    }

    public function rules(): array
    {
        return [
            'entrepot_source_id'      => ['required', 'exists:entrepots,id', 'different:entrepot_destination_id'],
            'entrepot_destination_id' => ['required', 'exists:entrepots,id'],
            'materiau_id'             => ['required', 'exists:materiaux,id'],
            'quantite'                => ['required', 'numeric', 'gt:0'],
        ];
    }
}