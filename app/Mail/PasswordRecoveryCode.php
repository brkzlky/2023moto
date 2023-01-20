<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Log;

class PasswordRecoveryCode extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.recovery_code')
                    ->with([
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                        'recovery_code' => $this->user->pw_recovery_code,
                        'attempt' => $this->user->pw_recovery_attempt,
                        'validity' => $this->user->pw_recovery_validity,
                        'url' => 'https://member.motovago.test/recovery-code'
                    ]);
    }
}
