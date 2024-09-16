@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Stripe Details</h2>
    <p><strong>Name:</strong> {{ $customer->name }}</p>

    <h3>Subscriptions</h3>
    <ul>
        @foreach ($subscriptions->data as $subscription)
            <li>Plan: {{ $subscription->plan->nickname }} - Status: {{ $subscription->status }}</li>
        @endforeach
    </ul>

    <h3>Last Invoice</h3>
    @if (count($lastInvoice->data) > 0)
        <p>Amount Paid: ${{ $lastInvoice->data[0]->amount_paid / 100 }}</p>
        <p>Date: {{ date('Y-m-d', $lastInvoice->data[0]->created) }}</p>
    @else
        <p>No invoices found.</p>
    @endif
</div>
@endsection
