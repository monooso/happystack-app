@component('mail::message')
Hello,

The status of the **{{ $component->service->name }} {{ $component->name }}** service in the **{{ $project->name }}** project has changed.

The new status is **{{ $component->status }}**.

Best regards,<br />
Stackbot 🤖
@endcomponent
