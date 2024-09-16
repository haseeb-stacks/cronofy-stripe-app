<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class CronofyController extends Controller
{
    protected $client;
    protected $cronofyUrl = 'https://api.cronofy.com';

    public function __construct()
    {
        $this->client = new Client();
    }

    public function index()
    {
        // Check if user already has a token
        $user = Auth::user();
        if (!$user->cronofy_access_token) {
            // Redirect to Cronofy for authentication
            return redirect($this->getCronofyAuthUrl());
        }

        // Fetch user calendars
        $calendars = $this->getUserCalendars($user->cronofy_access_token);
        return view('cronofy.index', compact('calendars'));
    }

    public function callback(Request $request)
    {
        // Exchange code for access token
        $code = $request->input('code');
        $token = $this->exchangeToken($code);

        // Store token in the user's profile
        $user = Auth::user();
        $user->cronofy_access_token = $token->access_token;
        $user->save();

        return redirect('/cronofy');
    }

    private function getCronofyAuthUrl()
    {
        $params = http_build_query([
            'client_id' => env('CRONOFY_CLIENT_ID'),
            'redirect_uri' => env('CRONOFY_REDIRECT_URI'),
            'response_type' => 'code',
            'scope' => 'read_account read_events',
        ]);

        return "https://app.cronofy.com/oauth/authorize?$params";
    }

    private function exchangeToken($code)
    {
        $response = $this->client->post("{$this->cronofyUrl}/oauth/token", [
            'form_params' => [
                'client_id' => env('CRONOFY_CLIENT_ID'),
                'client_secret' => env('CRONOFY_CLIENT_SECRET'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => env('CRONOFY_REDIRECT_URI'),
            ],
        ]);

        return json_decode($response->getBody());
    }

    private function getUserCalendars($accessToken)
    {
        $response = $this->client->get("{$this->cronofyUrl}/v1/calendars", [
            'headers' => [
                'Authorization' => "Bearer $accessToken",
            ],
        ]);

        return json_decode($response->getBody(), true)['calendars'];
    }
}
