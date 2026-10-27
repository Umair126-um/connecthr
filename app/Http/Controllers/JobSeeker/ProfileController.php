<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Certification;
use App\Models\Language;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user    = auth()->user()->load('profile', 'skills', 'educations', 'experiences', 'certifications', 'languages');
        $skills  = Skill::orderBy('name')->get();
        return view('dashboard.profile.edit', compact('user', 'skills'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'phone'            => 'nullable|string|max:20',
            'title'            => 'nullable|string|max:100',
            'bio'              => 'nullable|string|max:1000',
            'city'             => 'nullable|string|max:100',
            'country'          => 'nullable|string|max:100',
            'date_of_birth'    => 'nullable|date',
            'gender'           => 'nullable|in:male,female,other,prefer_not_to_say',
            'nationality'      => 'nullable|string|max:100',
            'expected_salary'  => 'nullable|string|max:50',
            'availability'     => 'nullable|string',
            'experience_years' => 'nullable|string|max:20',
            'portfolio_url'    => 'nullable|url',
            'linkedin_url'     => 'nullable|url',
        ]);

        $user = auth()->user();
        $user->update(['name' => $request->name, 'phone' => $request->phone]);
        $user->profile()->updateOrCreate(['user_id' => $user->id], $request->except(['name', 'phone', '_token', '_method']));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:2048']);
        $user = auth()->user();
        if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);
        return back()->with('success', 'Profile picture updated.');
    }

    public function uploadCv(Request $request)
    {
        $request->validate(['cv' => 'required|mimes:pdf,doc,docx|max:5120']);
        $user = auth()->user();
        if ($user->profile?->cv_file) {
            Storage::disk('public')->delete($user->profile->cv_file);
        }
        $path = $request->file('cv')->store('cvs', 'public');
        $user->profile()->update(['cv_file' => $path]);
        return back()->with('success', 'CV uploaded successfully.');
    }

    public function deleteCv()
    {
        $user = auth()->user();
        if ($user->profile?->cv_file) {
            Storage::disk('public')->delete($user->profile->cv_file);
            $user->profile()->update(['cv_file' => null]);
        }
        return back()->with('success', 'CV deleted.');
    }

    // Education CRUD
    public function addEducation(Request $request)
    {
        $request->validate([
            'degree'         => 'required|string|max:100',
            'institution'    => 'required|string|max:200',
            'field_of_study' => 'nullable|string|max:100',
            'start_year'     => 'required|integer|min:1950|max:' . (date('Y') + 4),
            'end_year'       => 'nullable|integer|min:1950|max:' . (date('Y') + 4),
            'is_current'     => 'boolean',
        ]);
        auth()->user()->educations()->create($request->all());
        return back()->with('success', 'Education added.');
    }

    public function updateEducation(Request $request, int $id)
    {
        $edu = Education::where('user_id', auth()->id())->findOrFail($id);
        $edu->update($request->all());
        return back()->with('success', 'Education updated.');
    }

    public function deleteEducation(int $id)
    {
        Education::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Education deleted.');
    }

    // Experience CRUD
    public function addExperience(Request $request)
    {
        $request->validate([
            'job_title'    => 'required|string|max:100',
            'company_name' => 'required|string|max:200',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after:start_date',
            'is_current'   => 'boolean',
            'description'  => 'nullable|string|max:1000',
        ]);
        auth()->user()->experiences()->create($request->all());
        return back()->with('success', 'Experience added.');
    }

    public function updateExperience(Request $request, int $id)
    {
        Experience::where('user_id', auth()->id())->findOrFail($id)->update($request->all());
        return back()->with('success', 'Experience updated.');
    }

    public function deleteExperience(int $id)
    {
        Experience::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Experience deleted.');
    }

    // Certifications
    public function addCertification(Request $request)
    {
        $request->validate(['name' => 'required|string|max:200', 'issuing_organization' => 'required|string|max:200']);
        auth()->user()->certifications()->create($request->all());
        return back()->with('success', 'Certification added.');
    }

    public function updateCertification(Request $request, int $id)
    {
        Certification::where('user_id', auth()->id())->findOrFail($id)->update($request->all());
        return back()->with('success', 'Certification updated.');
    }

    public function deleteCertification(int $id)
    {
        Certification::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Certification deleted.');
    }

    // Languages
    public function addLanguage(Request $request)
    {
        $request->validate(['language' => 'required|string|max:50', 'proficiency' => 'required|in:basic,conversational,fluent,native']);
        auth()->user()->languages()->create($request->all());
        return back()->with('success', 'Language added.');
    }

    public function deleteLanguage(int $id)
    {
        Language::where('user_id', auth()->id())->findOrFail($id)->delete();
        return back()->with('success', 'Language deleted.');
    }

    // Skills
    public function updateSkills(Request $request)
    {
        $request->validate(['skills' => 'array', 'skills.*' => 'exists:skills,id']);
        auth()->user()->skills()->sync(
            collect($request->skills ?? [])->mapWithKeys(fn($id) => [$id => ['level' => 'intermediate']])
        );
        return back()->with('success', 'Skills updated.');
    }
}
