{{-- resources/views/emails/job-alert.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}.wrap{max-width:600px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}.hdr{background:linear-gradient(135deg,#2563eb,#1d4ed8);padding:32px 40px;text-align:center}.hdr h1{color:#fff;margin:0;font-size:22px;font-weight:700}.hdr p{color:#bfdbfe;margin:8px 0 0;font-size:14px}.body{padding:32px 40px}.job-card{border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin:12px 0;display:block;text-decoration:none;color:inherit}.job-card:hover{border-color:#2563eb}.job-title{font-weight:700;color:#1e40af;font-size:15px;margin:0 0 4px}.job-meta{font-size:13px;color:#6b7280;margin:0 0 8px}.job-salary{font-size:13px;font-weight:600;color:#16a34a}.btn{display:inline-block;background:#2563eb;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;margin:20px 0}.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}</style></head>
<body>
<div class="wrap">
    <div class="hdr">
        <h1>🔔 {{ $jobs->count() }} New Job{{ $jobs->count() > 1 ? 's' : '' }} For You</h1>
        <p>Matching: {{ $alert->keyword ?: 'All Jobs' }}{{ $alert->location ? ' · '.$alert->location->city : '' }}</p>
    </div>
    <div class="body">
        <p style="color:#374151;font-size:14px">Here are the latest jobs matching your alert criteria:</p>

        @foreach($jobs->take(10) as $job)
        <a href="{{ route('jobs.show', $job->slug) }}" class="job-card">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <img src="{{ $job->company?->logo_url }}" style="width:40px;height:40px;border-radius:8px;object-fit:contain;background:#f3f4f6">
                <div>
                    <p class="job-title">{{ $job->title }}</p>
                    <p class="job-meta">{{ $job->company?->name }} · {{ $job->location?->city }}</p>
                </div>
            </div>
            <div style="display:flex;gap:8px;flex-wrap:wrap;font-size:12px">
                <span style="background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:999px">{{ $job->job_type_label }}</span>
                @if(!$job->hide_salary)<span class="job-salary">{{ $job->salary_range }}</span>@endif
                <span style="color:#9ca3af">{{ timeAgo($job->created_at) }}</span>
            </div>
        </a>
        @endforeach

        <div style="text-align:center">
            <a href="{{ route('jobs.index') }}" class="btn">View All Jobs →</a>
        </div>
    </div>
    <div class="footer">
        <p>ConnectHR · Pakistan's Job Portal</p>
        <p><a href="{{ route('alerts.unsubscribe', ['email' => $alert->email]) }}" style="color:#94a3b8">Unsubscribe from alerts</a></p>
    </div>
</div>
</body>
</html>
