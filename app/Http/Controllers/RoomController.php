<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\SentMessage;
use App\Models\Message;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('room', [
            'data' => Message::with('user')->get()
        ]);
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
