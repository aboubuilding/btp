<?php
namespace App\Http\Requests\Communication;

use App\Domain\Socle\Models\Communication;
use Illuminate\Foundation\Http\FormRequest;

class StoreCommunicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Communication::class);
    }

    public function rules(): array
    {
        return [
            'sujet'                 => ['required', 'string', 'max:255'],
            'corps'                 => ['required', 'string', 'max:10000'],
            'destinataires'         => ['required', 'array', 'min:1', 'max:100'],
            'destinataires.*.email' => ['required', 'email'],
            'destinataires.*.nom'   => ['nullable', 'string', 'max:150'],
            'pieces_jointes'        => ['nullable', 'array', 'max:5'],
            'pieces_jointes.*'      => ['file', 'max:10240'],
            'envoyer_maintenant'    => ['nullable', 'boolean'],
            'planifie_le'           => ['nullable', 'date', 'after:now'],
        ];
    }
}