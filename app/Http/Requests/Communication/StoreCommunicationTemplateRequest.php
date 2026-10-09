<?php
namespace App\Http\Requests\Communication;

use App\Domain\Socle\Models\CommunicationTemplate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommunicationTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', CommunicationTemplate::class);
    }

    public function rules(): array
    {
        $templateId = $this->route('template')?->id;

        return [
            'code'           => ['required', 'string', 'max:100', Rule::unique('communication_templates', 'code')->ignore($templateId)],
            'nom'            => ['required', 'string', 'max:255'],
            'sujet_template' => ['required', 'string', 'max:255'],
            'corps_template' => ['required', 'string', 'max:20000'],
            'variables'      => ['nullable', 'array'],
        ];
    }
}