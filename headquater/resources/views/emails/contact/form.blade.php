<x-mail::message>
# New Contact Form Submission

You have received a new message from your website's contact form.

**From:** {{ $details['name'] }}
**Email:** [{{ $details['email'] }}](mailto:{{ $details['email'] }})

**Message:**
{{ $details['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>