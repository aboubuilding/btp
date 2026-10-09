<?php
namespace App\Http\Requests\Personnel;

use Illuminate\Foundation\Http\FormRequest;

class ResilierContratRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('rh', 'direction', 'admin');
    }

    public function rules(): array
    {
        return [
            'date_fin' => ['required', 'date', 'before_or_equal:today'],
            'motif'    => ['required', 'string', 'max:500'],
        ];
    }
}