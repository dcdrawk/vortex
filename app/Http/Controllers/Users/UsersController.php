<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $users = DB::table('users')->get();

        return view('user.index', ['users' => $users]);
    }

    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function user()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Not Authorized.');
            // return response()->json([
            //     'success' => false,
            //     'user' => undefined
            // ]);
        }
        return response()->json([
            'success' => true,
            'user' => $user
        ]);
        // }
        // return $user;
        // $users = DB::table('users')->get();

        // return view('user.index', ['users' => $users]);
    }
}