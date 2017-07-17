<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd($request);
        if (Auth::guard($guard)->check()) {
            $email = $request->input('email');
            $user = DB::table('users')->where('email', $email)->first();

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
            // return 'Hello there!';
            // return redirect('/home');
        }

        return $next($request);
    }
}
