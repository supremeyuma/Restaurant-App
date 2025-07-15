<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:120',
            'price'             => 'required|numeric|min:0',
            'description'       => 'nullable|string',
            'wait_time_minutes' => 'required|integer|min:1|max:120',
            'image'             => $this->isMethod('post')
                                        ? 'nullable|image|max:2048' // create
                                        : 'nullable|image|max:2048', // update
            'is_available'      => 'sometimes|boolean',
        ];
    }
}
