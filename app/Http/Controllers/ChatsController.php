<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class ChatsController extends Controller
{
    
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show chats
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat');
    }

    public function fetchMessages(Request $request) : JsonResponse {
        return response()->json(["messages" => Message::with('user')->get()->toArray()]);
    }

    /**
     * Persist message to database
     *
     * @param  Request $request
     * @return Response
    */
    public function sendMessage(Request $request)
    {
        $user = Auth::user();

        $message = $user->messages()->create([
            'message' => $request->input('message')
        ]);

        broadcast(new MessageSent($user, $message))->toOthers();

        return ['success' => true];
    }

    /**
     * Remove message from a database
     *
     * @param  Request $request
     * @return Response
     */
    public function deleteMessage(Request $request)
    {
        // $user = Auth::user();
        //

        return ['status' => '200 OK'];
    }


}
