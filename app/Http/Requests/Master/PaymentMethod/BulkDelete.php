<?php

namespace App\Http\Requests\Master\PaymentMethod;

use App\Traits\PaymentMethod\PaymentMethodTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BulkDelete extends FormRequest
{
    use PaymentMethodTrait;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::authorize("bulk-delete-{$this->prefixPermission()}");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => ['array', 'required']
        ];
    }
}
