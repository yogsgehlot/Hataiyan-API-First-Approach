<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class AuthController extends Controller
{

    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function register(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        // make validator data available as array
        $validated = $validator->validated();
        // dd($validated);die;
        try {
            $response = $this->apiService->post('/register', $validated);

            if ($response['success']) {
                if (isset($response['data']['token'])) {
                    $this->apiService->updateToken($response['data']['token']);
                }
                session(['user' => $response['data']['user'] ?? null]);

                return redirect()->route('home')
                    ->with('success', $response['message'] ?? 'Registration successful');
            }

            return back()->withErrors(['api_error' => $response['message'] ?? 'Registration failed'])
                ->withInput();

        } catch (\Throwable $e) {
            Log::error('Registration Exception', ['error' => $e->getMessage()]);

            return back()->withErrors(['api_error' => 'Something went wrong, please try again later.'])
                ->withInput();
        }
    }


    public function login(Request $request)
    {


        $credentials = $request->validate([
            'identifier' => 'required|string', // can be email or username
            'password' => 'required|min:6'
        ]);

        try {
            $response = $this->apiService->post('/login', $credentials);
            if ($response['success']) {

                if (isset($response['data']['token'])) {
                    $this->apiService->updateToken($response['data']['token']);
                }
                session(['user' => $response['data']['user'] ?? null]);
                return redirect()->route('home')
                    ->with('success', $response['message'] ?? 'Login successful');
            }
            return back()->withErrors(['api_error' => $response['message'] ?? 'Login failed'])
                ->withInput();

        } catch (\Throwable $e) {
            Log::error('Login Exception', ['error' => $e->getMessage()]);

            return back()->withErrors(['api_error' => 'Something went wrong, please try again later.'])
                ->withInput();
        }

    }


    public function logout(Request $request)
    {
        try {

            $response = $this->apiService->post('/logout', []);

            // clear local token and session regardless of API response

            $this->apiService->updateToken(null);
            $request->session()->flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if (!empty($response['success']) && $response['success']) {
                return redirect()->route('login')->with('success', $response['message'] ?? 'Logged out successfully.');
            }

            return redirect()->route('login')->withErrors(['api_error' => $response['message'] ?? 'Logout failed.']);
        } catch (\Throwable $e) {
            $this->apiService->updateToken(" ");
            $request->session()->flush();
            
            Log::error('Logout Exception', ['error' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['api_error' => 'Something went wrong, please try again later.']);
        }
    }
}
