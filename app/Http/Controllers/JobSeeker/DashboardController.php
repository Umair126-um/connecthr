<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Application;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $stats = [
            'applied'     => Application::where('user_id', $user->id)->count(),
            'saved'       => $user->savedJobs()->count(),
            'shortlisted' => Application::where('user_id', $user->id)->where('status', 'shortlisted')->count(),
            'interviews'  => Application::where('user_id', $user->id)->where('status', 'interview')->count(),
        ];

        $recentApplications = Application::with(['job.company', 'job.location'])
            ->where('user_id', $user->id)
            ->latest()->take(5)->get();

        $savedJobs = $user->savedJobs()->with(['company', 'location'])->latest()->take(4)->get();

        $profile    = $user->profile;
        $completion = $this->calcProfileCompletion($user);

        return view('dashboard.index', compact('stats', 'recentApplications', 'savedJobs', 'completion', 'profile'));
    }

    private function calcProfileCompletion($user): int
    {
        $checks = [
            $user->profile?->bio,
            $user->profile?->city,
            $user->avatar,
            $user->profile?->cv_file,
            $user->profile?->title,
            $user->skills()->exists(),
            $user->experiences()->exists(),
            $user->educations()->exists(),
            $user->phone,
        ];
        $filled = count(array_filter($checks));
        $pct    = (int)(($filled / count($checks)) * 100);
        $user->profile?->update(['profile_completion' => $pct]);
        return $pct;
    }
}
