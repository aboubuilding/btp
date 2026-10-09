<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateParametreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $parametreId = $this->route('parametre')->id;

        return [
            'cle'         => ['required', 'string', 'max:100', Rule::unique('parametres', 'cle')->ignore($parametreId)],
            'valeur'      => ['required', 'string'],
            'type'        => ['required', Rule::in(['string', 'decimal', 'integer', 'boolean', 'json'])],
            'description' => ['nullable', 'string', 'max:255'],
        ];
    }
}