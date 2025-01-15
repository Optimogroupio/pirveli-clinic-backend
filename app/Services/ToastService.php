<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;

class ToastService
{
    protected $message;
    protected $type = 'info';
    protected $position = 'bottom-right';
    protected $autoDismiss = 5;

    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function position(string $position): self
    {
        $this->position = $position;
        return $this;
    }

    public function autoDismiss(int $seconds): self
    {
        $this->autoDismiss = $seconds;
        return $this;
    }

    public function send(): void
    {
        Session::flash('toast', [
            'message' => $this->message,
            'type' => $this->type,
            'position' => $this->position,
            'autoDismiss' => $this->autoDismiss,
        ]);
    }
}
