<?php declare(strict_types=1);

namespace App\GraphQL\Validators\Mutation;

use Nuwave\Lighthouse\Validation\Validator;

final class CreateAppointmentValidator extends Validator
{
    /**
     * Return the validation rules.
     *
     * @return array<string, array<mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'surname' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'specialty_id' => [
                'nullable',
                'exists:specialties,id',
            ],
            'doctor_id' => [
                'nullable',
                'exists:doctors,id',
            ],
            'phone' => [
                'required',
                'regex:/^(\+995\d{9}|032\d{6}|\d{9})$/',
            ]
        ];
    }

    /**
     * Return custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number must be in one of the formats: xxxxxxxxx, +995xxxxxxxxx, or 032xxxxxxx.',
        ];
    }
}
