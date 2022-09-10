<?php

namespace App\Http\Controllers;

use App\Events\ChattingMessages;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;  
use App\Http\Controllers\PushNotificationController;
use Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = new Message;
        $message->conversation_id = $request->conversation_id;
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        $message->save();
        $conversation = $message->conversation;
        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed ="1";
            $conversation->receiver_viewed ="0";
            
            // for notification
            $id = $conversation->receiver_id; 
        }elseif($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed ="1";
            $conversation->sender_viewed ="0";

            // for notification
            $id = $conversation->sender_id;
        }
        $conversation->save();
        $data = [
            'conversation_id' => $request->conversation_id,
            'user_id' => Auth::user()->id,
            'message' => $request->message,
        ]; 
        
        $user = User::find($id);
        $title = Auth::user()->name;
        $body = $message->message;
        if($user->device_token != null){
            $tokens = array();
            array_push($tokens,$user->device_token);  
            $push_controller = new PushNotificationController();
            $push_controller->sendNotification($title, $body, $tokens, '#');
        }

        event(new ChattingMessages($data));
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
