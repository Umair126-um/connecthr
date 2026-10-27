<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Job, Company, Application, Category, Location, Skill, Page, Blog, Faq, Setting, PageView};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// ─── Admin Dashboard ─────────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_jobs'      => Job::count(),
            'pending_jobs'    => Job::where('status', 'pending')->count(),
            'total_users'     => User::where('role', '!=', 'admin')->count(),
            'total_companies' => Company::count(),
            'pending_companies' => Company::where('verification_status', 'pending')->count(),
            'total_applications' => Application::count(),
            'total_seekers'   => User::where('role', 'job_seeker')->count(),
            'total_employers' => User::where('role', 'employer')->count(),
        ];

        // Chart data — jobs per month
        $jobsChart = Job::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')->pluck('count', 'month')->toArray();

        $applicationsChart = Application::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')->pluck('count', 'month')->toArray();

        $recentJobs     = Job::with('company')->latest()->take(5)->get();
        $recentUsers    = User::where('role', '!=', 'admin')->latest()->take(5)->get();
        $recentApplications = Application::with(['user', 'job'])->latest()->take(5)->get();
        $pendingCompanies   = Company::where('verification_status', 'pending')->with('user')->take(5)->get();

        return view('admin.dashboard', compact(
            'stats', 'jobsChart', 'applicationsChart',
            'recentJobs', 'recentUsers', 'recentApplications', 'pendingCompanies'
        ));
    }

    public function applications(Request $request)
    {
        $query = Application::with(['user', 'job.company']);
        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('job_id'))  $query->where('job_id', $request->job_id);
        $applications = $query->latest()->paginate(20);
        return view('admin.applications', compact('applications'));
    }

    public function exportApplications()
    {
        $apps = Application::with(['user', 'job.company'])->get();
        $csv  = "Name,Email,Job Title,Company,Status,Applied At\n";
        foreach ($apps as $a) {
            $csv .= implode(',', [
                $a->user->name, $a->user->email,
                $a->job->title, $a->job->company->name,
                $a->status, $a->created_at->format('Y-m-d'),
            ]) . "\n";
        }
        return response($csv)->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="applications.csv"');
    }

    public function cvs(Request $request)
    {
        $profiles = \App\Models\JobSeekerProfile::with('user')
            ->whereNotNull('cv_file')->paginate(20);
        return view('admin.cvs', compact('profiles'));
    }

    public function downloadCv(int $id)
    {
        $profile = \App\Models\JobSeekerProfile::findOrFail($id);
        if (!$profile->cv_file) return back()->with('error', 'No CV found.');
        $path = Storage::disk('public')->path($profile->cv_file);
        return response()->download($path, 'CV-' . $profile->user->name . '.pdf');
    }

    public function bulkDownloadCvs()
    {
        $profiles = \App\Models\JobSeekerProfile::whereNotNull('cv_file')->get();
        $zip      = new \ZipArchive();
        $zipName  = storage_path('app/public/cvs-bulk-' . time() . '.zip');
        $zip->open($zipName, \ZipArchive::CREATE);
        foreach ($profiles as $p) {
            $file = Storage::disk('public')->path($p->cv_file);
            if (file_exists($file)) $zip->addFile($file, 'CV-' . $p->user->name . '.pdf');
        }
        $zip->close();
        return response()->download($zipName)->deleteFileAfterSend();
    }
}

// ─── Admin Users ─────────────────────────────────────────────────────────────
class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('role'))    $query->where('role', $request->role);
        if ($request->filled('q'))       $query->where(fn($q) => $q->where('name', 'like', "%{$request->q}%")->orWhere('email', 'like', "%{$request->q}%"));
        if ($request->filled('status'))  $query->where('is_active', $request->status);
        $users = $query->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(int $id)
    {
        $user = User::with('profile', 'company', 'skills', 'applications')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function destroy(int $id)
    {
        User::where('role', '!=', 'admin')->findOrFail($id)->delete();
        return back()->with('success', 'User deleted.');
    }

    public function toggleStatus(int $id)
    {
        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function verify(int $id)
    {
        User::findOrFail($id)->update(['is_verified' => true]);
        return back()->with('success', 'User verified.');
    }

    public function impersonate(int $id)
    {
        $user = User::findOrFail($id);
        session(['impersonating' => auth()->id()]);
        auth()->loginUsingId($id);
        return redirect('/')->with('warning', 'You are now logged in as ' . $user->name);
    }
}

// ─── Admin Jobs ──────────────────────────────────────────────────────────────
class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = Job::with(['company', 'category', 'location'])->withCount('applications');
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('q'))        $query->where('title', 'like', "%{$request->q}%");
        if ($request->filled('category')) $query->where('category_id', $request->category);
        $jobs       = $query->latest()->paginate(20);
        $categories = Category::active()->get();
        return view('admin.jobs.index', compact('jobs', 'categories'));
    }

    public function show(int $id)
    {
        $job = Job::with(['company', 'skills', 'applications.user'])->findOrFail($id);
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(int $id)
    {
        $job        = Job::findOrFail($id);
        $categories = Category::active()->get();
        $locations  = Location::active()->get();
        return view('admin.jobs.edit', compact('job', 'categories', 'locations'));
    }

    public function update(Request $request, int $id)
    {
        Job::findOrFail($id)->update($request->except(['_token', '_method']));
        return back()->with('success', 'Job updated.');
    }

    public function destroy(int $id)
    {
        Job::findOrFail($id)->delete();
        return back()->with('success', 'Job deleted.');
    }

    public function approve(int $id)
    {
        Job::findOrFail($id)->update(['status' => 'approved', 'approved_at' => now()]);
        // Notify employer
        $job = Job::with('user')->find($id);
        try { \Mail::to($job->user->email)->send(new \App\Mail\JobApproved($job)); } catch(\Exception $e) {}
        return back()->with('success', 'Job approved.');
    }

    public function reject(Request $request, int $id)
    {
        Job::findOrFail($id)->update(['status' => 'rejected', 'rejection_reason' => $request->reason]);
        return back()->with('success', 'Job rejected.');
    }

    public function toggleFeatured(int $id)
    {
        $job = Job::findOrFail($id);
        $job->update(['is_featured' => !$job->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }

    public function bulk(Request $request)
    {
        $request->validate(['action' => 'required', 'ids' => 'required|array']);
        $jobs = Job::whereIn('id', $request->ids);
        match($request->action) {
            'approve' => $jobs->update(['status' => 'approved', 'approved_at' => now()]),
            'reject'  => $jobs->update(['status' => 'rejected']),
            'delete'  => $jobs->delete(),
            'feature' => $jobs->update(['is_featured' => true]),
            default   => null,
        };
        return back()->with('success', 'Bulk action applied.');
    }
}

// ─── Admin Companies ─────────────────────────────────────────────────────────
class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with('user');
        if ($request->filled('status')) $query->where('verification_status', $request->status);
        if ($request->filled('q'))      $query->where('name', 'like', "%{$request->q}%");
        $companies = $query->latest()->paginate(20);
        return view('admin.companies.index', compact('companies'));
    }

    public function show(int $id)
    {
        $company = Company::with(['user', 'jobs'])->findOrFail($id);
        return view('admin.companies.show', compact('company'));
    }

    public function destroy(int $id)
    {
        Company::findOrFail($id)->delete();
        return back()->with('success', 'Company deleted.');
    }

    public function verify(int $id)
    {
        Company::findOrFail($id)->update([
            'is_verified' => true, 'verification_status' => 'verified', 'verified_at' => now(),
        ]);
        return back()->with('success', 'Company verified.');
    }

    public function reject(Request $request, int $id)
    {
        Company::findOrFail($id)->update([
            'verification_status' => 'rejected', 'rejection_reason' => $request->reason,
        ]);
        return back()->with('success', 'Company rejected.');
    }

    public function toggleFeatured(int $id)
    {
        $c = Company::findOrFail($id);
        $c->update(['is_featured' => !$c->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }
}

// ─── Admin Category / Location / Skill ──────────────────────────────────────
class CategoryController extends Controller
{
    public function index()    { return view('admin.categories.index', ['categories' => Category::withCount('jobs')->orderBy('order')->get()]); }
    public function store(Request $request) { $request->validate(['name' => 'required|unique:categories,name']); Category::create(array_merge($request->all(), ['slug' => \Str::slug($request->name)])); return back()->with('success', 'Category added.'); }
    public function update(Request $request, int $id) { Category::findOrFail($id)->update($request->except(['_token','_method'])); return back()->with('success', 'Category updated.'); }
    public function destroy(int $id) { Category::findOrFail($id)->delete(); return back()->with('success', 'Category deleted.'); }
}

class LocationController extends Controller
{
    public function index()    { return view('admin.locations', ['locations' => Location::orderBy('order')->get()]); }
    public function store(Request $request) { $request->validate(['city' => 'required']); Location::create($request->all()); return back()->with('success', 'Location added.'); }
    public function update(Request $request, int $id) { Location::findOrFail($id)->update($request->except(['_token','_method'])); return back()->with('success', 'Updated.'); }
    public function destroy(int $id) { Location::findOrFail($id)->delete(); return back()->with('success', 'Deleted.'); }
}

class SkillController extends Controller
{
    public function index()    { return view('admin.skills', ['skills' => Skill::orderBy('name')->get()]); }
    public function store(Request $request) { $request->validate(['name' => 'required|unique:skills,name']); Skill::create($request->all()); return back()->with('success', 'Skill added.'); }
    public function update(Request $request, int $id) { Skill::findOrFail($id)->update($request->except(['_token','_method'])); return back()->with('success', 'Updated.'); }
    public function destroy(int $id) { Skill::findOrFail($id)->delete(); return back()->with('success', 'Deleted.'); }
}

// ─── Admin CMS / Blog / FAQ ──────────────────────────────────────────────────
class PageController extends Controller
{
    public function index()    { return view('admin.cms.index', ['pages' => Page::all()]); }
    public function edit(int $id) { return view('admin.cms.edit', ['page' => Page::findOrFail($id)]); }
    public function update(Request $request, int $id) { Page::findOrFail($id)->update($request->except(['_token','_method'])); return back()->with('success', 'Page updated.'); }
    public function store(Request $request) { $request->validate(['title'=>'required','slug'=>'required|unique:pages,slug']); Page::create($request->all()); return back()->with('success','Page created.'); }
    public function destroy(int $id) { Page::findOrFail($id)->delete(); return back()->with('success','Deleted.'); }
}

class BlogController extends Controller
{
    public function index()  { return view('admin.blog.index', ['blogs' => Blog::with('user')->latest()->paginate(15)]); }
    public function create() { return view('admin.blog.form', ['blog' => new Blog()]); }
    public function store(Request $request) {
        $request->validate(['title'=>'required','content'=>'required']);
        $data = $request->except(['_token','thumbnail']);
        if ($request->hasFile('thumbnail')) $data['thumbnail'] = $request->file('thumbnail')->store('blogs','public');
        $data['user_id'] = auth()->id();
        $data['slug']    = \Str::slug($request->title) . '-' . time();
        $data['published_at'] = $request->status === 'published' ? now() : null;
        Blog::create($data);
        return redirect()->route('admin.blogs.index')->with('success','Blog created.');
    }
    public function edit(int $id)  { return view('admin.blog.form', ['blog' => Blog::findOrFail($id)]); }
    public function update(Request $request, int $id) { $blog = Blog::findOrFail($id); $data = $request->except(['_token','_method','thumbnail']); if ($request->hasFile('thumbnail')) $data['thumbnail'] = $request->file('thumbnail')->store('blogs','public'); if (!$blog->published_at && $request->status === 'published') $data['published_at'] = now(); $blog->update($data); return back()->with('success','Blog updated.'); }
    public function destroy(int $id) { Blog::findOrFail($id)->delete(); return back()->with('success','Deleted.'); }
}

class FaqController extends Controller
{
    public function index()  { return view('admin.faqs', ['faqs' => Faq::orderBy('order')->get()]); }
    public function store(Request $request)  { $request->validate(['question'=>'required','answer'=>'required']); Faq::create($request->all()); return back()->with('success','FAQ added.'); }
    public function update(Request $request, int $id) { Faq::findOrFail($id)->update($request->except(['_token','_method'])); return back()->with('success','Updated.'); }
    public function destroy(int $id) { Faq::findOrFail($id)->delete(); return back()->with('success','Deleted.'); }
}

// ─── Admin Settings ──────────────────────────────────────────────────────────
class SettingController extends Controller
{
    public function index()  { return view('admin.settings.index', ['settings' => Setting::where('group','general')->get()->keyBy('key')]); }
    public function update(Request $request) {
        foreach ($request->except(['_token','_method']) as $key => $value) {
            Setting::where('key', $key)->update(['value' => is_array($value) ? json_encode($value) : $value]);
        }
        return back()->with('success', 'Settings updated.');
    }
    public function seo()  { return view('admin.settings.seo', ['settings' => Setting::where('group','seo')->get()->keyBy('key')]); }
    public function updateSeo(Request $request) { return $this->update($request); }
}

// ─── Admin Reports ───────────────────────────────────────────────────────────
class ReportController extends Controller
{
    public function index()
    {
        $data = [
            'jobs_this_month'   => Job::whereMonth('created_at', now()->month)->count(),
            'users_this_month'  => User::whereMonth('created_at', now()->month)->count(),
            'apps_this_month'   => Application::whereMonth('created_at', now()->month)->count(),
            'top_categories'    => Category::withCount(['jobs' => fn($q) => $q->active()])->orderByDesc('jobs_count')->take(5)->get(),
            'top_companies'     => Company::withCount('applications')->orderByDesc('applications_count')->take(5)->get(),
            'top_jobs'          => Job::orderByDesc('views')->take(5)->get(),
        ];
        return view('admin.reports.index', compact('data'));
    }

    public function jobs()        { return view('admin.reports.jobs', ['jobs' => Job::with('company')->latest()->paginate(20)]); }
    public function users()       { return view('admin.reports.users', ['users' => User::latest()->paginate(20)]); }
    public function applications(){ return view('admin.reports.applications', ['applications' => Application::with(['user','job'])->latest()->paginate(20)]); }

    public function export(string $type)
    {
        $csv = match($type) {
            'jobs'  => $this->exportJobs(),
            'users' => $this->exportUsers(),
            default => '',
        };
        return response($csv)->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', "attachment; filename=\"{$type}-report.csv\"");
    }

    private function exportJobs(): string
    {
        $rows = ["Title,Company,Category,Status,Applications,Views,Posted\n"];
        Job::with(['company','category'])->get()->each(fn($j) =>
            $rows[] = implode(',', [$j->title, $j->company?->name, $j->category?->name, $j->status, $j->applications_count ?? 0, $j->views, $j->created_at->format('Y-m-d')]) . "\n"
        );
        return implode('', $rows);
    }

    private function exportUsers(): string
    {
        $rows = ["Name,Email,Role,Joined\n"];
        User::all()->each(fn($u) =>
            $rows[] = implode(',', [$u->name, $u->email, $u->role, $u->created_at->format('Y-m-d')]) . "\n"
        );
        return implode('', $rows);
    }
}
