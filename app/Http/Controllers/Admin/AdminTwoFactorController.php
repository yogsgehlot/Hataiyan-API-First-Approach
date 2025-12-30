<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class AdminTwoFactorController extends Controller
{
    public function index(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        //    dd($admin);
        return view('admin.2fa.enable', [
            'user' => $admin
        ]);
    }

    public function store(Request $request)
    {

        $admin = Auth::guard('admin')->user();
        // dd(get_class_methods(EnableTwoFactorAuthentication::class));

        app(EnableTwoFactorAuthentication::class)($admin);

        return back()->with('status', 'Two-factor authentication enabled.');
    }

    public function confirm(Request $request)
    {
        try {

            $admin = Auth::guard('admin')->user();
            if ($request->filled('recovery_code')) {

                $recoveryCode = $request->input('recovery_code');

                // Check if code exists
                if (!in_array($recoveryCode, $admin->recoveryCodes())) {
                    return back()->withErrors([
                        'recovery_code' => 'Invalid recovery code.',
                    ]);
                }

                // Invalidate used recovery code
                $admin->replaceRecoveryCode($recoveryCode);

                // Mark session as verified
                session(['admin_2fa_passed' => true]);

                return redirect()->route('admin.dashboard');
            }

            if (
                $request->code
            ) {
                app(ConfirmTwoFactorAuthentication::class)(
                    $admin,
                    $request->code
                );
                session(['admin_2fa_passed' => true]);
                return redirect()->route('admin.dashboard')
                    ->with('success', '2FA confirmed successfully.');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Invalid authentication code.');
        }
    }

    public function destroy(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        app(DisableTwoFactorAuthentication::class)($admin);

        return back()->with('success', 'Two-factor authentication disabled.');
    }
}
