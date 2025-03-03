<?php

namespace App\GraphQL\Queries;

use App\Models\Doctor;

class OtherDoctorsQuery
{
    public function otherDoctors($root, array $args)
    {
        return Doctor::where('id', '!=', $root->id)
            ->get();
    }
}
