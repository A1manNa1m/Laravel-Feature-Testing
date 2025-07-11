<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $method = $this->method();
        if($method == 'PUT'){
            return [
                'user_id' => ['required','integer'],
                'bio' => ['required','string'],
                'profile_image' => ['required','string'],
            ];
        }else{
            return [
                'user_id' => ['sometimes','required','integer'],
                'bio' => ['sometimes','required','string'],
                'profile_image' => ['sometimes','required','string'],
            ];
        }
    }
}
