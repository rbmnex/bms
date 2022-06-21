<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Models\Bridge;

class TaskNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $bridge;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Bridge $bridge, User $user)
    {
        $this->bridge = $bridge;
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.task-notice');
    }
}
