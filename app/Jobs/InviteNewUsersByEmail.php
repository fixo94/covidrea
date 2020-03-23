<?php

namespace App\Jobs;

use Str;
use Mail;
use App\Invite;
use App\Mail\InviteCreated;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InviteNewUsersByEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Emails
     *
     * @var array
     */
    protected $emails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emails)
    {
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Creating the Invite models
        foreach($this->emails as $email) {
            $this->createInviteModel($email);
        }
    }

    /**
     * Create new Invites from their email
     *
     * @param mixed $email
     */
    protected function createInviteModel($email)
    {
        //generate a random string using Laravel's str_random helper
        do {
            $token = Str::random();
        } //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

        //create a new invite record
        $invite = Invite::create([
            'email' => $email,
            'token' => $token
        ]);

        $this->sendInvitationEmail($invite);

        //@todo: send confirmation by email
    }

    /**
     * Send the emails to the invitee
     *
     * @param mixed $invite
     */
    protected function sendInvitationEmail($invite)
    {
        // send the email
        Mail::to($invite->email)->send(new InviteCreated($invite));
    }
}