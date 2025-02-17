<?php declare(strict_types=1);

namespace App\GraphQL\Validators\Mutation;

use Illuminate\Support\Facades\App;
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
        $locale = App::getLocale();

        $validation_messages = [
            'name' => [
                'required' => 'სახელი აუცილებელია',
                'string' => 'სახელი უნდა იყოს სტრიქონი',
                'min' => 'სახელი უნდა შედგებოდეს მინიმუმ 3 სიმბოლოსგან',
                'max' => 'სახელი უნდა შედგებოდეს მაქსიუმუმ 255 სიმბოლოსგან',
            ],
            'surname' => [
                'required' => 'გვარი აუცილებელია',
                'string' => 'გვარი უნდა იყოს სტრიქონი',
                'min' => 'გვარი უნდა შედგებოდეს მინიმუმ 3 სიმბოლოსგან',
                'max' => 'გვარი უნდა შედგებოდეს მაქსიუმუმ 255 სიმბოლოსგან',
            ],
            'specialty_id' => [
                'exists' => 'სპეციალობა მითითებული აიდით არ მოიძებნა'
            ],
            'doctor_id' => [
                'exists' => 'ექიმი მითითებული აიდით არ მოიძებნა'
            ],
            'phone' => [
                'required' => 'ტელეფონის ნომერი აუცილებელია',
                'regex' => 'ნომრის ფორმატი უნდა ემთხვეოდეს რომელიმეს: xxxxxxxxx, +995xxxxxxxxx, ან 032xxxxxxx.',
            ]
        ];

        $messages = [
            'ka' => $validation_messages,
            'en' => $validation_messages,
            'ru' => $validation_messages,
        ];

        return $messages[$locale];
    }
}
