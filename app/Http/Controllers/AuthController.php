<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        Log::info('Login attempt started', ['username' => $request->username]);

        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                Log::info('Authentication successful', ['username' => $credentials['username']]);
                $request->session()->regenerate();

                return $this->sendLoginResponse($request, true, 'Login Berhasil', route('home'));
            }

            Log::warning('Authentication failed', ['username' => $credentials['username']]);
            return $this->sendLoginResponse($request, false, 'Username atau password salah.', null, 422);

        } catch (ValidationException $e) {
            Log::error('Validation error: ' . $e->getMessage());
            return $this->sendLoginResponse($request, false, $e->errors(), null, 422);
        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->sendLoginResponse($request, false, 'Terjadi kesalahan. Silakan coba lagi nanti.', null, 500);
        }
    }

    protected function sendLoginResponse(Request $request, bool $status, string $message, ?string $redirect = null, int $statusCode = 200)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'redirect' => $redirect
            ], $statusCode);
        }

        if ($status) {
            return redirect()->intended($redirect);
        }

        return back()->withErrors(['error' => $message]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        Log::info('User logged out', ['user_id' => $user ? $user->user_id : null]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil',
                'redirect' => route('login')
            ]);
        }

        return redirect()->route('login')->with('status', 'Anda telah berhasil logout.');
    }
}
