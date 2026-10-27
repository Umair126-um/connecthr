<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-brand-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-white font-display">Connect<span class="text-brand-400">HR</span></span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed mb-4">
                    {{ setting('site_tagline', 'Find Your Dream Job in Pakistan') }}
                </p>
                <div class="flex gap-3">
                    @if(setting('facebook_url'))<a href="{{ setting('facebook_url') }}" target="_blank" class="text-gray-400 hover:text-brand-400 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>@endif
                    @if(setting('linkedin_url'))<a href="{{ setting('linkedin_url') }}" target="_blank" class="text-gray-400 hover:text-brand-400 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>@endif
                </div>
            </div>

            {{-- For Job Seekers --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">For Job Seekers</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('jobs.index') }}" class="hover:text-brand-400 transition">Browse Jobs</a></li>
                    <li><a href="{{ route('companies.index') }}" class="hover:text-brand-400 transition">Browse Companies</a></li>
                    @auth @if(auth()->user()->isJobSeeker())
                    <li><a href="{{ route('seeker.index') }}" class="hover:text-brand-400 transition">My Dashboard</a></li>
                    <li><a href="{{ route('seeker.profile') }}" class="hover:text-brand-400 transition">My Profile</a></li>
                    @endif @endauth
                    @guest
                    <li><a href="{{ route('register') }}" class="hover:text-brand-400 transition">Create Account</a></li>
                    @endguest
                </ul>
            </div>

            {{-- For Employers --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">For Employers</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('register.employer') }}" class="hover:text-brand-400 transition">Post a Job</a></li>
                    @auth @if(auth()->user()->isEmployer())
                    <li><a href="{{ route('employer.dashboard') }}" class="hover:text-brand-400 transition">Dashboard</a></li>
                    <li><a href="{{ route('employer.jobs') }}" class="hover:text-brand-400 transition">My Jobs</a></li>
                    @endif @endauth
                </ul>
            </div>

            {{-- Company --}}
            <div>
                <h4 class="font-semibold text-white mb-4 text-sm uppercase tracking-wider">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-brand-400 transition">About Us</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-brand-400 transition">Blog</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-brand-400 transition">Contact</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-brand-400 transition">FAQ</a></li>
                    <li><a href="{{ route('page.show', 'privacy-policy') }}" class="hover:text-brand-400 transition">Privacy Policy</a></li>
                    <li><a href="{{ route('page.show', 'terms-conditions') }}" class="hover:text-brand-400 transition">Terms & Conditions</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ setting('site_name', 'ConnectHR') }}. All rights reserved.</p>
            <p>Built with ❤️ for Pakistan's job market</p>
        </div>
    </div>
</footer>
