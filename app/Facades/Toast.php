<?php

namespace App\Facades;

use App\Services\ToastService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static ToastService title(string $title)
 * @method static ToastService message(string $message)
 * @method static ToastService type(string $type)
 * @method static ToastService position(string $position)
 * @method static ToastService autoDismiss(int $seconds = 5)
 * @method static ToastService send()
 */
class Toast extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'toast';
    }
}
