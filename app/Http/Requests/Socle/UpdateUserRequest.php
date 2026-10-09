<?php
namespace App\Http\Requests\Socle;

use App\Domain\Socle\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $this->user()->can('update', $user) || $this->user()->id === $user->id;
    }

    public function rules(): array
    {
        $userId = $this->route('user')->id;

        return [
            'nom'          => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($userId)],
            'telephone'    => ['nullable', 'string', 'max:30'],
            'role_id'      => ['required', 'exists:roles,id'],
            'mot_de_passe' => ['nullable', 'confirmed', Password::min(6)],
            'est_actif'    => ['nullable', 'boolean'],
        ];
    }
}