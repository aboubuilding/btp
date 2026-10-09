<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $permissionId = $this->route('permission')?->id;

        return [
            'nom'    => ['required', 'string', 'max:100'],
            'slug'   => ['required', 'string', 'max:100', Rule::unique('permissions', 'slug')->ignore($permissionId)],
            'module' => ['required', Rule::in(['M1','M2','M3','M4','M5','M6','M7','M8','M9','M10','M11','M12'])],
        ];
    }
}