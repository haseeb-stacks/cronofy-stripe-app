<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Invoice;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
    }

    public function index()
    {
        $user = Auth::user();

        // Assuming user has a stripe_customer_id saved in the DB
        if (!$user->stripe_customer_id) {
            return redirect()->route('home')->with('error', 'No Stripe account linked.');
        }

        $customer = Customer::retrieve($user->stripe_customer_id);
        $subscriptions = Subscription::all(['customer' => $user->stripe_customer_id]);
        $lastInvoice = Invoice::all(['customer' => $user->stripe_customer_id, 'limit' => 1]);

        return view('stripe.index', compact('customer', 'subscriptions', 'lastInvoice'));
    }
}
