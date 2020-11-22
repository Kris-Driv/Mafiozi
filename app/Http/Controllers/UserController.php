<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function top(Request $request) : JsonResponse
    {
        $type = $request->sort_by ?? "level";

        $users = User::join('stats', 'stats.user_id', '=', 'users.id')
        ->where('type', '=', $type)
        ->orderBy('value', 'desc')
        ->select('users.*', 'stats.value', 'stats.max', 'stats.type')
        ->get();

        $users->makeHidden(['created_at', 'updated_at', 'email_verified_at']);

        // W.I.P.
        return response()->json($users->toArray());
    }

}
