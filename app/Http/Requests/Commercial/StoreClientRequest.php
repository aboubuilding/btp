<?php
namespace App\Http\Requests\Commercial;

use App\Domain\Commercial\Models\Client;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Client::class);
    }

    public function rules(): array
    {
        return [
            'nom'       => ['required', 'string', 'max:255'],
            'type'      => ['required', Rule::in(['particulier', 'entreprise', 'public'])],
            'contact'   => ['nullable', 'string', 'max:150'],
            'telephone' => ['nullable', 'string', 'max:30'],
            'email'     => ['nullable', 'email', 'max:150'],
            'nif'       => ['nullable', 'string', 'max:50'],
            'adresse'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Le type de client doit être particulier, entreprise ou public.',
        ];
    }
}