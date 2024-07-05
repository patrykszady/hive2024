<x-mail::message>
Hi {{$vendor->name}},
<br>
Payment details from <b>{{$paying_vendor->name}}</b>:
<x-mail::panel>
Check <a href="https://dashboard.hive.contractors/checks/{{$check->id}}"><b>{{$check_number}}</b></a>
<br>
Check Date <b>{{$check->date->format('m/d/Y')}}</b><br>
Check Total <b>{{money($check->amount)}}</b><br>
</x-mail::panel>
<h3>Project Payments:</h3>
<x-mail::panel>
@foreach($check->expenses as $expense)
<b>{{money($expense->amount)}}</b> | <a href="https://dashboard.hive.contractors/projects/{{$expense->project->id}}">{{$expense->project->name}}</a>
<br>
@endforeach
</x-mail::panel>
<x-mail::subcopy>
{{$paying_vendor->name}} uses <a href="https://dashboard.hive.contractors">Hive Contractors</a> to manage projects in one place. <b>Finances, Estimates, Timesheets, Schedules</b>, and so much more.
<br>
Join <a href="https://dashboard.hive.contractors">Hive Contractors</a> to connect with {{$paying_vendor->name}} today to manage your construction projects, better, together.
</x-mail::subcopy>
<x-mail::button :url="'https://dashboard.hive.contractors'">
Create Your Hive
</x-mail::button>
</x-mail::message>
