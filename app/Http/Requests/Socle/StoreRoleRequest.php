<?php
namespace App\Http\Requests\Socle;

use App\Domain\Socle\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Role::class);
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

    protected function prepareForValidation(): void
    {
        if ($this->has('slug')) {
            $this->merge(['slug' => strtolower(trim($this->slug))]);
        }
    }
}