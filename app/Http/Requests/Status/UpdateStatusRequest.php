<?php

namespace App\Http\Requests\Status;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    /**
     * 
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $statusId = $this->route('status') ? $this->route('status')->id : null;
        return [
            'name' => ['sometimes',
            'required',
            'string',
            'max:255',
            Rule::unique('statuses')
            ->ignore($statusId)]
        ];
    }
}
