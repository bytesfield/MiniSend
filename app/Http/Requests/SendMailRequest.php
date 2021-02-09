<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $method = $this->method();

        if (null !== $this->get('_method', null)) {

            $method = $this->get('_method');
        }
        $this->offsetUnset('_method');

        switch ($method) {

            case 'DELETE':
                $this->rules = [];
                break;

            case 'GET':
                $this->rules = [];
                break;

            case 'POST':
                $this->rules = [
                    'from' => ['required', 'string'],
                    'to' => ['required', 'string'],
                    'subject' => ['required', 'string'],
                    'content' => ['required', 'string'],
                ];
                break;

            case 'PUT':
                $this->rules = [];
                break;

            case 'PATCH':
                $this->rules = [];
                break;

            default:
                break;
        }

        return $this->rules;
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'from.required' => 'Sender Email is required!',
            'to.required' => 'Recipient Email is required!',
            'subject.required' => 'Subject is required!',
            'content.required' => 'Content is required!'
        ];
    }
}
