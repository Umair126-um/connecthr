<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::active()->verified()->withCount(['jobs' => fn($q) => $q->active()]);

        if ($request->filled('q'))        $query->where('name', 'like', "%{$request->q}%");
        if ($request->filled('category')) $query->where('category_id', $request->category);
        if ($request->filled('size'))     $query->where('company_size', $request->size);

        $companies  = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('order')->get();

        return view('public.companies.index', compact('companies', 'categories'));
    }

    public function show(string $slug)
    {
        $company = Company::where('slug', $slug)->active()->firstOrFail();
        $jobs    = $company->jobs()->active()->with(['category', 'location'])->latest()->paginate(10);

        return view('public.companies.show', compact('company', 'jobs'));
    }
}
