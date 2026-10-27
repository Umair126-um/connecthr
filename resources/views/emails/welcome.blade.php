{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}
.wrap{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.hdr{background:linear-gradient(135deg,#16a34a,#15803d);padding:40px;text-align:center}
.hdr h1{color:#fff;margin:0;font-size:26px;font-weight:800}
.body{padding:32px 40px}
.btn{display:inline-block;background:#16a34a;color:#fff;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;margin:24px 0}
.step{display:flex;align-items:flex-start;gap:16px;margin:16px 0}
.step-num{background:#16a34a;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0}
.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}
</style></head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>Welcome to ConnectHR! 👋</h1>
    </div>
    <div class="body">
        <p style="font-size:16px;color:#374151">Hi <strong>{{ $user->name }}</strong>,</p>
        <p style="color:#6b7280;font-size:14px;line-height:1.7">Your ConnectHR account is ready. {{ $user->isJobSeeker() ? "Pakistan's leading employers are waiting to discover your talent." : "Start connecting with top talent across Pakistan." }}</p>

        @if($user->isJobSeeker())
        <p style="font-weight:700;color:#374151;margin:24px 0 12px">Here's how to get started:</p>
        <div class="step"><div class="step-num">1</div><div style="font-size:14px;color:#4b5563">Complete your profile — upload your CV, add your skills, and write a compelling bio</div></div>
        <div class="step"><div class="step-num">2</div><div style="font-size:14px;color:#4b5563">Browse thousands of jobs across Pakistan — filter by city, category, or job type</div></div>
        <div class="step"><div class="step-num">3</div><div style="font-size:14px;color:#4b5563">Apply with one click and track all your applications in your dashboard</div></div>
        <div class="step"><div class="step-num">4</div><div style="font-size:14px;color:#4b5563">Set up job alerts to receive relevant opportunities directly in your inbox</div></div>
        @else
        <div class="step"><div class="step-num">1</div><div style="font-size:14px;color:#4b5563">Set up your company profile with logo, description, and industry details</div></div>
        <div class="step"><div class="step-num">2</div><div style="font-size:14px;color:#4b5563">Post your first job — it goes live after a quick review</div></div>
        <div class="step"><div class="step-num">3</div><div style="font-size:14px;color:#4b5563">Review applications and manage your hiring pipeline from your dashboard</div></div>
        @endif

        <div style="text-align:center">
            <a href="{{ url('/') }}" class="btn">{{ $user->isJobSeeker() ? 'Explore Jobs Now →' : 'Post Your First Job →' }}</a>
        </div>
    </div>
    <div class="footer"><p>ConnectHR · Pakistan's Job Portal · <a href="{{ url('/') }}" style="color:#16a34a">connecthr.pk</a></p></div>
</div>
</body>
</html>


{{-- ─── job-approved.blade.php ───────────────────────────────────────────────
     resources/views/emails/job-approved.blade.php (inline below for brevity) --}}
