<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $availablePaymentMethods = ['cod'];

        if (filled(config('manual-payments.bkash.merchant_number'))) {
            $availablePaymentMethods[] = 'bkash';
        }

        if (filled(config('manual-payments.nagad.merchant_number'))) {
            $availablePaymentMethods[] = 'nagad';
        }

        return [
            'address_id' => [
                'required',
                'integer',
                Rule::exists('addresses', 'id')
                    ->where(fn ($query) => $query->where(
                        'user_id',
                        $this->user()->id
                    )),
            ],

            'delivery_method' => [
                'required',
                'in:standard,express',
            ],

            'payment_method' => [
                'required',
                Rule::in($availablePaymentMethods),
            ],

            'payment_reference' => [
                'exclude_unless:payment_method,bkash,nagad',
                'required',
                'string',
                'max:255',
            ],

            'payment_payer_number' => [
                'exclude_unless:payment_method,bkash,nagad',
                'required',
                'string',
                'max:30',
            ],

            'customer_note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'address_id.required' =>
                'Please select a delivery address.',

            'address_id.exists' =>
                'The selected delivery address is invalid.',

            'delivery_method.required' =>
                'Please select a delivery method.',

            'delivery_method.in' =>
                'The selected delivery method is invalid.',

            'payment_method.required' =>
                'Please select a payment method.',

            'payment_method.in' =>
                'The selected payment method is not available.',

            'payment_reference.required' =>
                'Please enter your payment transaction ID.',

            'payment_payer_number.required' =>
                'Please enter the phone number used for payment.',

            'customer_note.max' =>
                'The delivery note cannot exceed 1000 characters.',
        ];
    }
}
