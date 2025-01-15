<?php

namespace App\GraphQL\Queries;

use App\Models\News;

class OtherNewsQuery
{
    public function relatedNews($root, array $args)
    {
        return News::where('id', '!=', $root->id)
            ->take(4)
            ->get();
    }
}
