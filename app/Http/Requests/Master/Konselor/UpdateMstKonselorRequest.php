<?php

namespace App\Http\Requests\Master\Konselor;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMstKonselorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        if (!$this->isEksternal) {
            return [
            ];
        }
        $rules = [
            'username' => 'required|unique:Users,username,'.$this->UserId,
            'email' => 'required|email',
        ];
        if ($this->changePassword) {
            $rules['new_password']=  'required|confirmed|min:6';
        }
        return $rules;
    }
}
