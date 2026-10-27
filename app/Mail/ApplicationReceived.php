<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// ─── Application Received (to Employer) ─────────────────────────────────────
class ApplicationReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function build()
    {
        return $this
            ->subject('New Application – ' . $this->application->job->title)
            ->view('emails.application-received');
    }
}

// ─── Application Status Updated (to Job Seeker) ─────────────────────────────
class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Application $application) {}

    public function build()
    {
        return $this
            ->subject('Your Application Status – ' . $this->application->job->title)
            ->view('emails.application-status');
    }
}

// ─── Job Approved (to Employer) ─────────────────────────────────────────────
class JobApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\Job $job) {}

    public function build()
    {
        return $this
            ->subject('Your Job is Now Live – ' . $this->job->title)
            ->view('emails.job-approved');
    }
}

// ─── Contact Form (to Admin) ─────────────────────────────────────────────────
class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function build()
    {
        return $this
            ->subject('Contact Form: ' . $this->data['subject'])
            ->replyTo($this->data['email'], $this->data['name'])
            ->view('emails.contact');
    }
}

// ─── Job Alert Digest ────────────────────────────────────────────────────────
class JobAlertDigest extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public \App\Models\JobAlert $alert,
        public \Illuminate\Support\Collection $jobs
    ) {}

    public function build()
    {
        return $this
            ->subject('New Jobs Matching Your Alert – ConnectHR')
            ->view('emails.job-alert');
    }
}

// ─── Welcome Email ───────────────────────────────────────────────────────────
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public \App\Models\User $user) {}

    public function build()
    {
        return $this
            ->subject('Welcome to ConnectHR!')
            ->view('emails.welcome');
    }
}
