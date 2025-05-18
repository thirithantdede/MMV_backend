<?php

namespace App\Http\Requests\Element;

use Illuminate\Foundation\Http\FormRequest;

class BaseElementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'x' => 'required|numeric',
            'y' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'rotation' => 'required|numeric',
            'color' => 'required|string',
            'icon' => 'required|string',
            'floor_id' => 'required|string',
        ];
    }
}
