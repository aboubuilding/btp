<?php
namespace App\Http\Requests\Socle;

use App\Domain\Socle\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', User::class);
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'nom'          => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($userId)],
            'telephone'    => ['nullable', 'string', 'max:30'],
            'role_id'      => ['required', 'exists:roles,id'],
            'mot_de_passe' => [$userId ? 'nullable' : 'required', 'confirmed', Password::min(6)],
            'est_actif'    => ['nullable', 'boolean'],
            'avatar'       => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'mot_de_passe.confirmed' => 'Les deux mots de passe ne correspondent pas.',
        ];
    }
}