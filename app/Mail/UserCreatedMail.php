<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreatedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $password;
    public $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $password, $code)
    {
        $this->user = $user;
        $this->password = $password;
        $this->code = $code;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Votre compte a été créé')
                    ->view('emails.user_created')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'code' => $this->code,
                    ]);
    }
}
