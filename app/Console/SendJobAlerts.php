<?php

namespace App\Console\Commands;

use App\Mail\JobAlertDigest;
use App\Models\Job;
use App\Models\JobAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendJobAlerts extends Command
{
    protected $signature   = 'alerts:send {--frequency=daily : daily or weekly}';
    protected $description = 'Send job alert emails to subscribers';

    public function handle(): void
    {
        $frequency = $this->option('frequency');
        $alerts    = JobAlert::active()->where('frequency', $frequency)->with(['category', 'location'])->get();

        $this->info("Processing {$alerts->count()} {$frequency} alerts...");

        $sent = 0;
        foreach ($alerts as $alert) {
            $query = Job::active()->latest();

            if ($alert->keyword)     $query->where(fn($q) => $q->where('title','like',"%{$alert->keyword}%")->orWhere('description','like',"%{$alert->keyword}%"));
            if ($alert->category_id) $query->where('category_id', $alert->category_id);
            if ($alert->location_id) $query->where('location_id', $alert->location_id);
            if ($alert->job_type)    $query->where('job_type', $alert->job_type);

            // Only jobs since last alert
            $since = $alert->last_sent_at ?? now()->subDays($frequency === 'daily' ? 1 : 7);
            $query->where('created_at', '>=', $since);

            $jobs = $query->take(10)->get();

            if ($jobs->isEmpty()) continue;

            try {
                Mail::to($alert->email)->send(new JobAlertDigest($alert, $jobs));
                $alert->update(['last_sent_at' => now()]);
                $sent++;
            } catch (\Exception $e) {
                $this->warn("Failed to send alert to {$alert->email}: " . $e->getMessage());
            }
        }

        $this->info("✓ Sent {$sent} alert emails.");
    }
}
