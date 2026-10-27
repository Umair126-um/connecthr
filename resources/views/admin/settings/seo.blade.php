<x-admin-layout>
<x-slot name="title">SEO Settings</x-slot>

<div class="max-w-3xl">
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm mb-5">✓ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.seo.update') }}" class="space-y-6">
        @csrf @method('PATCH')

        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Default SEO</h2>
            <div>
                <label class="label">Meta Title</label>
                <input type="text" name="meta_title" value="{{ setting('meta_title','') }}" class="input" placeholder="ConnectHR – Find Jobs in Pakistan">
                <p class="text-xs text-gray-400 mt-1">Recommended: 50-60 characters</p>
            </div>
            <div>
                <label class="label">Meta Description</label>
                <textarea name="meta_description" rows="3" class="textarea" placeholder="Pakistan's leading job portal…">{{ setting('meta_description','') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Recommended: 150-160 characters</p>
            </div>
            <div>
                <label class="label">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ setting('meta_keywords','') }}" class="input" placeholder="jobs pakistan, jobs lahore, job portal…">
            </div>
            <div>
                <label class="label">OG Image URL</label>
                <input type="url" name="og_image" value="{{ setting('og_image','') }}" class="input" placeholder="https://connecthr.pk/og-image.jpg">
                <p class="text-xs text-gray-400 mt-1">Used as thumbnail when link is shared on social media. Recommended: 1200x630px</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-6 space-y-4">
            <h2 class="font-semibold text-gray-800">Tracking & Verification</h2>
            <div>
                <label class="label">Google Analytics ID</label>
                <input type="text" name="google_analytics_id" value="{{ setting('google_analytics_id','') }}" class="input" placeholder="G-XXXXXXXXXX or UA-XXXXXXXXX">
            </div>
            <div>
                <label class="label">Google Search Console Verification</label>
                <input type="text" name="google_site_verification" value="{{ setting('google_site_verification','') }}" class="input" placeholder="Meta tag content value">
            </div>
            <div>
                <label class="label">Custom Head Scripts</label>
                <textarea name="head_scripts" rows="4" class="textarea font-mono text-xs" placeholder="&lt;script&gt;…&lt;/script&gt;">{{ setting('head_scripts','') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Injected inside &lt;head&gt; on every page</p>
            </div>
        </div>

        <button type="submit" class="btn-primary px-8">Save SEO Settings</button>
    </form>
</div>
</x-admin-layout>
