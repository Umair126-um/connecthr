<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Faq;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('public.pages.show', compact('page'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about-us')->first();
        return view('public.pages.about', compact('page'));
    }

    public function faq()
    {
        $faqs = Faq::where('is_active', true)->orderBy('order')->get()->groupBy('category');
        return view('public.pages.faq', compact('faqs'));
    }

    public function contact()
    {
        return view('public.pages.contact');
    }

    public function sendContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:20',
        ]);

        // Send email
        \Mail::to(setting('site_email', 'admin@connecthr.pk'))->send(
            new \App\Mail\ContactMail($request->all())
        );

        return back()->with('success', 'Your message has been sent. We will get back to you shortly.');
    }
}
