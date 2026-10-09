<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'nom'         => ['required', 'string', 'max:100'],
            'slug'        => ['required', 'string', 'max:100', 'regex:/^[a-z_]+$/', Rule::unique('roles', 'slug')->ignore($roleId)],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }
}