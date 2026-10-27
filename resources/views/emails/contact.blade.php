{{-- resources/views/emails/contact.blade.php --}}
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',sans-serif;background:#f8f9fa;margin:0;padding:20px}.wrap{max-width:580px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)}.hdr{background:linear-gradient(135deg,#374151,#1f2937);padding:32px 40px;text-align:center}.hdr h1{color:#fff;margin:0;font-size:22px;font-weight:700}.body{padding:32px 40px}.field{margin:12px 0;font-size:14px}.field-label{font-weight:700;color:#374151;display:block;margin-bottom:4px}.field-value{color:#6b7280;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:12px;display:block}.footer{background:#f1f5f9;padding:20px 40px;text-align:center;font-size:12px;color:#94a3b8}</style></head>
<body>
<div class="wrap">
    <div class="hdr"><h1>📬 New Contact Form Submission</h1></div>
    <div class="body">
        <div class="field"><span class="field-label">From</span><span class="field-value">{{ $data['name'] }} &lt;{{ $data['email'] }}&gt;</span></div>
        <div class="field"><span class="field-label">Subject</span><span class="field-value">{{ $data['subject'] }}</span></div>
        <div class="field"><span class="field-label">Message</span><span class="field-value" style="white-space:pre-wrap">{{ $data['message'] }}</span></div>
        <p style="margin-top:20px;font-size:13px;color:#9ca3af">Reply directly to this email to respond to {{ $data['name'] }}.</p>
    </div>
    <div class="footer"><p>ConnectHR Contact Form</p></div>
</div>
</body>
</html>
