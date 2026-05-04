<x-mail::message>
# Welcome to HATS HRMS, {{ $user->name }}!

Your employee account has been successfully created. You can now log in to the portal to view your employment details, check your leave entitlements, and access the system.

**Here are your login credentials:**

**Email:** {{ $user->email }}  
**Password:** {{ $plainPassword }}

<x-mail::panel>
**Important:** We highly recommend changing your password immediately after logging in for the first time.
</x-mail::panel>

<x-mail::button :url="route('login')">
Login to HRMS
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
