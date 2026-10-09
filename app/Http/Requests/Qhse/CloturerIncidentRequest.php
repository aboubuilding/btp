<?php
namespace App\Http\Requests\Qhse;

use Illuminate\Foundation\Http\FormRequest;

class CloturerIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('responsable_qhse', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'actions_correctives' => ['required', 'string', 'max:2000'],
        ];
    }
}