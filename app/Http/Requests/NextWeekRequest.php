<?php

namespace App\Http\Requests;

use App\Contract\JSONRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

class NextWeekRequest extends FormRequest
{
    private JSONRequest $JSONRequest;

    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->JSONRequest = new DefaultJSONRequest();
        parent::__construct($query, $request, $attributes, $cookies, $files, $server, $content);
    }

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
            'team_ids' => ['required', 'array', 'size:4'],
            'team_ids.*' => ['integer']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->JSONRequest->throwJSONResponseException($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
    }
}
