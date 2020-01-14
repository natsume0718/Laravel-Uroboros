<?php

namespace App\Http\Requests\Activity;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tweet' => 'required',
            'hour' => 'required|date_format:H:i',
            'is_reply' => 'boolean'
        ];
    }

    public function attributes()
    {
        return [
            'tweet' => 'ツイート内容',
            'hour' => '活動時間',
            'is_reply' => 'リプライ形式'
        ];
    }
}
