<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AuthorizeUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = ''): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $request->user();

        // Jika role kosong, langsung lewatkan
        if (empty($role)) {
            return $next($request);
        }

        // Periksa jika method hasRole ada
        if (!method_exists($user, 'hasRole')) {
            abort(500, 'Method hasRole tidak ditemukan di model User');
        }

        // Cek multiple roles (jika role dipisahkan dengan |)
        $roles = explode('|', $role);
        foreach ($roles as $r) {
            if ($user->hasRole(trim($r))) {
                return $next($request);
            }
        }

        // Jika tidak punya role yang sesuai
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Forbidden. Anda tidak memiliki akses ke resource ini.'
            ], 403);
        }

        abort(403, 'Forbidden. Anda tidak memiliki akses ke halaman ini.');
    }
}
