<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProposalRequest extends FormRequest
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
                'user_id' => ['required', 'integer'],
                'project_id' => ['required', 'integer'],
                'cover_letter' => ['required','string'],
            ];
        }else{
            return [
                'user_id' => ['sometimes','required', 'integer'],
                'project_id' => ['sometimes','required', 'integer'],
                'cover_letter' => ['sometimes','required','string'],
            ];
        }
    }
}
