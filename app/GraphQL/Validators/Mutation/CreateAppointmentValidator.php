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

        $messages =  [
            'ka' => [
                'name.required' => 'სახელი აუცილებელია',
                'name.string' => 'სახელი უნდა იყოს სტრიქონი',
                'name.min' => 'სახელი უნდა შედგებოდეს მინიმუმ 3 სიმბოლოსგან',
                'name.max' => 'სახელი უნდა შედგებოდეს მაქსიუმუმ 255 სიმბოლოსგან',
                'surname.required' => 'გვარი აუცილებელია',
                'surname.string' => 'გვარი უნდა იყოს სტრიქონი',
                'surname.min' => 'გვარი უნდა შედგებოდეს მინიმუმ 3 სიმბოლოსგან',
                'surname.max' => 'გვარი უნდა შედგებოდეს მაქსიუმუმ 255 სიმბოლოსგან',
                'specialty_id.exists' => 'სპეციალობა მითითებული აიდით არ მოიძებნა',
                'doctor_id.exists' => 'ექიმი მითითებული აიდით არ მოიძებნა',
                'phone.required' => 'ტელეფონის ნომერი აუცილებელია',
                'phone.regex' => 'ნომრის ფორმატი უნდა ემთხვეოდეს რომელიმეს: xxxxxxxxx, +995xxxxxxxxx, ან 032xxxxxxx.',
            ],
            'en' => [
                'name.required' => 'The name is required.',
                'name.string' => 'The name must be a string.',
                'name.min' => 'The name must be at least 3 characters long.',
                'name.max' => 'The name must not exceed 255 characters.',
                'surname.required' => 'The surname is required.',
                'surname.string' => 'The surname must be a string.',
                'surname.min' => 'The surname must be at least 3 characters long.',
                'surname.max' => 'The surname must not exceed 255 characters.',
                'specialty_id.exists' => 'The specified specialty ID was not found.',
                'doctor_id.exists' => 'The specified doctor ID was not found.',
                'phone.required' => 'The phone number is required.',
                'phone.regex' => 'The phone number format must be one of the following: xxxxxxxxx, +995xxxxxxxxx, or 032xxxxxxx.',
            ],
            'ru' => [
                'name.required' => 'Имя обязательно.',
                'name.string' => 'Имя должно быть строкой.',
                'name.min' => 'Имя должно содержать минимум 3 символа.',
                'name.max' => 'Имя должно содержать не более 255 символов.',
                'surname.required' => 'Фамилия обязательна.',
                'surname.string' => 'Фамилия должна быть строкой.',
                'surname.min' => 'Фамилия должна содержать минимум 3 символа.',
                'surname.max' => 'Фамилия должна содержать не более 255 символов.',
                'specialty_id.exists' => 'Специальность с указанным ID не найдена.',
                'doctor_id.exists' => 'Доктор с указанным ID не найден.',
                'phone.required' => 'Номер телефона обязателен.',
                'phone.regex' => 'Формат номера телефона должен соответствовать одному из следующих: xxxxxxxxx, +995xxxxxxxxx или 032xxxxxxx.',
            ],
        ];

        return $messages[$locale] ?? $messages['en'];
    }
}
