@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Calendars</h2>
    <ul>
        @foreach ($calendars as $calendar)
            <li>{{ $calendar['calendar_name'] }} - {{ $calendar['calendar_id'] }}</li>
        @endforeach
    </ul>
</div>
@endsection
