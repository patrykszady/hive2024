<x-mail::message>
<b>{{$verification_code}}</b> is your verification code for <a href="{{env('APP_URL')}}">Hive Contractors</a>
<x-mail::button :url="{{env('APP_URL')}}">
Continue Registration
</x-mail::button>
</x-mail::message>
