{{-- resources/views/emails/application-status.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}
.email-wrapper{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}
.header{padding:32px 40px;text-align:center}
.header.applied{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
.header.shortlisted{background:linear-gradient(135deg,#d97706,#b45309)}
.header.interview{background:linear-gradient(135deg,#7c3aed,#6d28d9)}
.header.hired{background:linear-gradient(135deg,#16a34a,#15803d)}
.header.rejected{background:linear-gradient(135deg,#dc2626,#b91c1c)}
.header h1{color:#fff;margin:0;font-size:22px;font-weight:700}
.header p{color:rgba(255,255,255,.8);margin:8px 0 0;font-size:14px}
.body{padding:32px 40px}
.status-badge{display:inline-block;padding:6px 16px;border-radius:999px;font-weight:700;font-size:14px;text-transform:uppercase;letter-spacing:.5px;margin:16px 0}
.btn{display:inline-block;background:#2563eb;color:#fff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;margin:20px 0}
.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}
</style></head>
<body>
<div class="email-wrapper">
    @php
        $statusMap = [
            'shortlisted' => ['emoji'=>'⭐','title'=>'You\'ve Been Shortlisted!','subtitle'=>'The employer is interested in your profile','color'=>'#d97706','bg'=>'#fffbeb','class'=>'shortlisted'],
            'interview'   => ['emoji'=>'🎯','title'=>'Interview Invitation','subtitle'=>'The employer wants to meet you','color'=>'#7c3aed','bg'=>'#f5f3ff','class'=>'interview'],
            'hired'       => ['emoji'=>'🎉','title'=>'Congratulations! You\'re Hired','subtitle'=>'Your hard work paid off','color'=>'#16a34a','bg'=>'#f0fdf4','class'=>'hired'],
            'rejected'    => ['emoji'=>'📋','title'=>'Application Update','subtitle'=>'An update on your application','color'=>'#dc2626','bg'=>'#fef2f2','class'=>'rejected'],
        ];
        $info = $statusMap[$application->status] ?? $statusMap['rejected'];
    @endphp
    <div class="header {{ $info['class'] }}">
        <h1>{{ $info['emoji'] }} {{ $info['title'] }}</h1>
        <p>{{ $info['subtitle'] }}</p>
    </div>
    <div class="body">
        <p style="color:#374151;font-size:15px">Hi <strong>{{ $application->user->name }}</strong>,</p>

        <div style="background:{{ $info['bg'] }};border-radius:12px;padding:20px;margin:16px 0">
            <p style="margin:0 0 8px;color:#374151;font-size:14px"><strong>Job:</strong> {{ $application->job->title }}</p>
            <p style="margin:0 0 8px;color:#374151;font-size:14px"><strong>Company:</strong> {{ $application->job->company?->name }}</p>
            <p style="margin:0;color:#374151;font-size:14px"><strong>Status:</strong>
                <span style="color:{{ $info['color'] }};font-weight:700">{{ ucfirst($application->status) }}</span>
            </p>
        </div>

        @if($application->status === 'rejected')
        <p style="color:#6b7280;font-size:14px;line-height:1.6">Thank you for your interest in {{ $application->job->title }}. After careful consideration, the employer has decided to move forward with other candidates. Please don't be discouraged — new opportunities are posted daily on ConnectHR.</p>
        @elseif($application->status === 'interview')
        <p style="color:#6b7280;font-size:14px;line-height:1.6">Excellent news! The employer wants to schedule an interview with you. Please check your dashboard for further details and instructions from the employer.</p>
        @elseif($application->status === 'shortlisted')
        <p style="color:#6b7280;font-size:14px;line-height:1.6">Your profile has caught the employer's attention and you've been shortlisted for the next stage. Stay tuned for further updates.</p>
        @elseif($application->status === 'hired')
        <p style="color:#6b7280;font-size:14px;line-height:1.6">Congratulations! You've been selected for this position. The employer will contact you directly with onboarding details. This is a massive achievement — well done!</p>
        @endif

        <a href="{{ route('seeker.applications') }}" class="btn">View Your Applications →</a>
    </div>
    <div class="footer">
        <p>ConnectHR · Pakistan's Job Portal</p>
    </div>
</div>
</body>
</html>
