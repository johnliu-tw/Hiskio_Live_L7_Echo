<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SentMessage;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('room');
    }

    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $message = $user->messages()->create([
            'message' => $request->data
        ]);

        broadcast(new SentMessage($user, $message));

        return true;
    }
}
