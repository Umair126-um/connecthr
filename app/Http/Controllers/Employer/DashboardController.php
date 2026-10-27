<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Application;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use App\Models\Skill;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

// ─── Employer Dashboard ─────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $company = $user->company;

        $stats = [
            'total_jobs'   => Job::where('user_id', $user->id)->count(),
            'active_jobs'  => Job::where('user_id', $user->id)->where('status', 'approved')->count(),
            'applications' => Application::where('company_id', $company?->id)->count(),
            'shortlisted'  => Application::where('company_id', $company?->id)->where('status', 'shortlisted')->count(),
        ];

        $recentApplicants = Application::with(['user.profile', 'job'])
            ->where('company_id', $company?->id)
            ->latest()->take(6)->get();

        $recentJobs = Job::where('user_id', $user->id)->with('applications')->latest()->take(5)->get();

        return view('employer.dashboard', compact('stats', 'recentApplicants', 'recentJobs', 'company'));
    }
}

// ─── Employer Company ────────────────────────────────────────────────────────
class CompanyController extends Controller
{
    public function edit()
    {
        $company    = auth()->user()->company ?? new Company();
        $categories = Category::active()->get();
        $locations  = Location::active()->get();
        return view('employer.company', compact('company', 'categories', 'locations'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'tagline'     => 'nullable|string|max:200',
            'description' => 'nullable|string|max:3000',
            'website'     => 'nullable|url',
            'email'       => 'nullable|email',
            'phone'       => 'nullable|string|max:20',
            'founded_year'=> 'nullable|integer|min:1800|max:' . date('Y'),
            'company_size'=> 'nullable|string',
        ]);

        $user    = auth()->user();
        $company = $user->company;

        if (!$company) {
            $company = Company::create(array_merge($request->except(['_token', '_method', 'logo']), [
                'user_id' => $user->id,
                'slug'    => \Str::slug($request->name) . '-' . $user->id,
            ]));
        } else {
            $company->update($request->except(['_token', '_method', 'logo']));
        }

        return back()->with('success', 'Company profile updated.');
    }

    public function uploadLogo(Request $request)
    {
        $request->validate(['logo' => 'required|image|max:2048']);
        $company = auth()->user()->company;
        if ($company->logo) Storage::disk('public')->delete($company->logo);
        $company->update(['logo' => $request->file('logo')->store('logos', 'public')]);
        return back()->with('success', 'Logo updated.');
    }
}

// ─── Employer Jobs ───────────────────────────────────────────────────────────
class JobController extends Controller
{
    public function index()
    {
        $jobs = Job::where('user_id', auth()->id())
            ->withCount('applications')->latest()->paginate(15);
        return view('employer.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $categories = Category::active()->get();
        $locations  = Location::active()->get();
        $skills     = Skill::orderBy('name')->get();
        return view('employer.jobs.create', compact('categories', 'locations', 'skills'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'            => 'required|string|max:200',
            'description'      => 'required|string|min:50',
            'category_id'      => 'nullable|exists:categories,id',
            'location_id'      => 'nullable|exists:locations,id',
            'job_type'         => 'required|in:full_time,part_time,contract,freelance,internship,remote',
            'experience_level' => 'required|in:entry,junior,mid,senior,executive',
            'deadline'         => 'nullable|date|after:today',
            'vacancies'        => 'nullable|integer|min:1',
        ]);

        $user = auth()->user();
        $job  = Job::create(array_merge($request->except(['_token', 'skills']), [
            'user_id'    => $user->id,
            'company_id' => $user->company?->id,
            'status'     => 'pending',
            'expires_at' => now()->addDays(30),
        ]));

        if ($request->filled('skills')) {
            $job->skills()->sync($request->skills);
        }

        return redirect()->route('employer.jobs')
            ->with('success', 'Job posted! It will be visible after admin approval.');
    }

    public function edit(int $id)
    {
        $job        = Job::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::active()->get();
        $locations  = Location::active()->get();
        $skills     = Skill::orderBy('name')->get();
        return view('employer.jobs.edit', compact('job', 'categories', 'locations', 'skills'));
    }

    public function update(Request $request, int $id)
    {
        $job = Job::where('user_id', auth()->id())->findOrFail($id);
        $job->update($request->except(['_token', '_method', 'skills']));
        if ($request->filled('skills')) $job->skills()->sync($request->skills);
        return redirect()->route('employer.jobs')->with('success', 'Job updated.');
    }

    public function destroy(int $id)
    {
        Job::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Job deleted.');
    }

    public function close(int $id)
    {
        Job::where('user_id', auth()->id())->findOrFail($id)->update(['status' => 'closed']);
        return back()->with('success', 'Job closed.');
    }

    public function reopen(int $id)
    {
        Job::where('user_id', auth()->id())->findOrFail($id)->update(['status' => 'pending']);
        return back()->with('success', 'Job submitted for re-approval.');
    }
}

// ─── Applicants ─────────────────────────────────────────────────────────────
class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $company = auth()->user()->company;
        $query   = Application::with(['user.profile', 'job'])
            ->where('company_id', $company?->id);

        if ($request->filled('job_id'))  $query->where('job_id', $request->job_id);
        if ($request->filled('status'))  $query->where('status', $request->status);

        $applications = $query->latest()->paginate(15);
        $jobs         = Job::where('user_id', auth()->id())->where('status', 'approved')->get();

        return view('employer.applicants.index', compact('applications', 'jobs'));
    }

    public function show(int $id)
    {
        $application = Application::with(['user.profile', 'user.skills', 'user.educations', 'user.experiences', 'job'])
            ->where('company_id', auth()->user()->company?->id)
            ->findOrFail($id);

        // Mark as viewed
        if (!$application->viewed_at) {
            $application->update(['status' => 'viewed', 'viewed_at' => now()]);
        }

        return view('employer.applicants.show', compact('application'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate(['status' => 'required|in:viewed,shortlisted,interview,rejected,hired']);

        $application = Application::where('company_id', auth()->user()->company?->id)->findOrFail($id);
        $application->update(['status' => $request->status]);

        // Notify job seeker via email
        try {
            Mail::to($application->user->email)->send(new ApplicationStatusUpdated($application));
        } catch (\Exception $e) {}

        return back()->with('success', 'Applicant status updated.');
    }

    public function downloadCv(int $id)
    {
        $application = Application::where('company_id', auth()->user()->company?->id)->findOrFail($id);

        if (!$application->cv_file) {
            return back()->with('error', 'No CV uploaded for this application.');
        }

        $path = Storage::disk('public')->path($application->cv_file);
        return response()->download($path, 'CV-' . $application->user->name . '.pdf');
    }
}
