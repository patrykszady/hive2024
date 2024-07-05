<x-mail::message>
<b>{{$verification_code}}</b> is your verification code.
<x-mail::subcopy>
Join <a href="{{env('APP_URL')}}">Hive Contractors</a> today to flawlessly manage your construction projects, see more details for this payment, add bids, and so much more!<br>
Call Patryk 224-999-3880 to setup for free!
</x-mail::subcopy>
<x-mail::button :url="{{env('APP_URL')}}">
Join Hive
</x-mail::button>
Thanks,<br>
Patryk<br>
<a href="{{env('APP_URL')}}">Hive Contractors</a>
</x-mail::message>
