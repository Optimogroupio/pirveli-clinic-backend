<?php

namespace App\GraphQL\Mutations;

use App\Models\Appointment;

class CreateAppointmentMutation
{
    /**
     * Handle the mutation.
     *
     * @param null $_
     * @param array $args
     * @return array
     */
    public function __invoke($_, array $args)
    {

        try {
            $appointment = Appointment::create([
                'name' => $args['name'],
                'surname' => $args['surname'],
                'phone' => $args['phone'],
                'comment' => $args['comment'],
            ]);


            return [
                'success' => true,
                'message' => __('messages.booking.success'),
                'appointment' => $appointment,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => __('messages.booking.error'),
                'appointment' => null,
            ];
        }
    }
}
