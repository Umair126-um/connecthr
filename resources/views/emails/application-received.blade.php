{{-- resources/views/emails/application-received.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}
.email-wrapper{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.header{background:linear-gradient(135deg,#16a34a,#15803d);padding:32px 40px;text-align:center}
.header h1{color:#fff;margin:0;font-size:22px;font-weight:700}
.header p{color:#bbf7d0;margin:8px 0 0;font-size:14px}
.body{padding:32px 40px}
.card{background:#f8fdf9;border:1px solid #dcfce7;border-radius:12px;padding:20px;margin:20px 0}
.info-row{display:flex;gap:12px;margin:8px 0;font-size:14px;color:#374151}
.info-label{font-weight:600;color:#16a34a;min-width:100px}
.btn{display:inline-block;background:#16a34a;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;margin:20px 0}
.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}
</style></head>
<body>
<div class="email-wrapper">
    <div class="header">
        <h1>📋 New Application Received</h1>
        <p>Someone just applied for your job posting</p>
    </div>
    <div class="body">
        <p style="color:#374151;font-size:15px">Hi <strong>{{ $application->job->user->name ?? 'there' }}</strong>,</p>
        <p style="color:#6b7280;font-size:14px">Great news! A new candidate has applied for your position. Here are the details:</p>

        <div class="card">
            <div class="info-row"><span class="info-label">Job Title</span>{{ $application->job->title }}</div>
            <div class="info-row"><span class="info-label">Applicant</span>{{ $application->user->name }}</div>
            <div class="info-row"><span class="info-label">Email</span>{{ $application->user->email }}</div>
            <div class="info-row"><span class="info-label">Applied</span>{{ $application->created_at->format('M d, Y \a\t h:i A') }}</div>
            @if($application->user->profile?->title)
            <div class="info-row"><span class="info-label">Title</span>{{ $application->user->profile->title }}</div>
            @endif
        </div>

        @if($application->cover_letter)
        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:20px;margin:20px 0">
            <p style="font-weight:600;color:#92400e;margin:0 0 8px">Cover Letter:</p>
            <p style="color:#78350f;font-size:14px;margin:0;line-height:1.6">{{ Str::limit($application->cover_letter, 300) }}</p>
        </div>
        @endif

        <a href="{{ route('employer.applicants.show', $application->id) }}" class="btn">View Full Application →</a>

        <p style="color:#9ca3af;font-size:13px">Review the candidate's complete profile, download their CV, and update their application status from your employer dashboard.</p>
    </div>
    <div class="footer">
        <p>ConnectHR · Pakistan's Job Portal</p>
        <p>You're receiving this because you posted a job on ConnectHR.</p>
    </div>
</div>
</body>
</html>
