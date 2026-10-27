{{-- resources/views/emails/job-approved.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}.wrap{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}.hdr{background:linear-gradient(135deg,#16a34a,#15803d);padding:32px 40px;text-align:center}.hdr h1{color:#fff;margin:0;font-size:22px;font-weight:700}.body{padding:32px 40px}.btn{display:inline-block;background:#16a34a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;margin:20px 0}.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}</style></head>
<body>
<div class="wrap">
    <div class="hdr"><h1>🎉 Your Job is Now Live!</h1></div>
    <div class="body">
        <p style="color:#374151;font-size:15px">Hi <strong>{{ $job->user->name ?? 'there' }}</strong>,</p>
        <p style="color:#6b7280;font-size:14px">Your job posting has been approved and is now visible to thousands of job seekers on ConnectHR.</p>
        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:20px;margin:16px 0">
            <p style="margin:0 0 8px;font-weight:700;color:#15803d;font-size:15px">{{ $job->title }}</p>
            <p style="margin:0;color:#4b5563;font-size:13px">{{ $job->company?->name }} · {{ $job->location?->city }}</p>
        </div>
        <a href="{{ route('jobs.show', $job->slug) }}" class="btn">View Your Job Listing →</a>
        <p style="color:#9ca3af;font-size:13px">You'll receive email notifications when candidates apply. Manage all applications from your employer dashboard.</p>
    </div>
    <div class="footer"><p>ConnectHR · Pakistan's Job Portal</p></div>
</div>
</body>
</html>
