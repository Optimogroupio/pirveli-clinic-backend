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
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'phone' => [
                'required',
                'regex:/^(\+995\d{9}|032\d{6}|\d{9})$/',
            ],
            'comment' => [
                'required',
                'string',
                'min:10',
                'max:500',
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
                'full_name.required' => 'სახელი აუცილებელია',
                'full_name.string' => 'სახელი უნდა იყოს სტრიქონი',
                'full_name.min' => 'სახელი უნდა შედგებოდეს მინიმუმ 3 სიმბოლოსგან',
                'full_name.max' => 'სახელი უნდა შედგებოდეს მაქსიუმუმ 255 სიმბოლოსგან',
                'comment.required' => 'კომენტარი აუცილებელია',
                'comment.string' => 'კომენტარი უნდა იყოს სტრიქონი',
                'comment.min' => 'კომენტარი უნდა შედგებოდეს მინიმუმ 10 სიმბოლოსგან',
                'comment.max' => 'კომენტარი უნდა შედგებოდეს მაქსიუმუმ 500 სიმბოლოსგან',
                'phone.required' => 'საკონტაქტო ინფორმაცია ნომერი აუცილებელია',
                'phone.regex' => 'საკონტაქტო ინფორმაციის ფორმატი უნდა ემთხვეოდეს რომელიმეს: xxxxxxxxx, +995xxxxxxxxx, ან 032xxxxxxx.',
            ],
            'en' => [
                'full_name.required' => 'The full_name is required.',
                'full_name.string' => 'The full_name must be a string.',
                'full_name.min' => 'The full_name must be at least 3 characters long.',
                'full_name.max' => 'The full_name must not exceed 255 characters.',
                'phone.required' => 'The  contact information  is required.',
                'phone.regex' => 'The contact information format must be one of the following: xxxxxxxxx, +995xxxxxxxxx, or 032xxxxxxx.',
            ],
            'ru' => [
                'full_name.required' => 'Имя обязательно.',
                'full_name.string' => 'Имя должно быть строкой.',
                'full_name.min' => 'Имя должно содержать минимум 3 символа.',
                'full_name.max' => 'Имя должно содержать не более 255 символов.',
                'phone.required' => 'Контактная информация обязательна.',
                'phone.regex' => 'Формат контактной информации должен соответствовать одному из следующих: xxxxxxxxx, +995xxxxxxxxx или 032xxxxxxx.',
            ],
        ];

        return $messages[$locale] ?? $messages['en'];
    }
}
