@component('mail::message')
Hello,

The status of the **{{ $service }}** service in the **{{ $project }}** project has changed.

**The new status is {{ $status }}**.

Best regards,<br />
Stackbot 🤖
@endcomponent
