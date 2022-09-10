<?php

namespace App\Http\Controllers\Admin\Conversations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use App\Models\User; 
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use App\Models\Product;
use Auth;

class ConversationController extends Controller
{

    public function new_contact(Request $request){
        $conversation = new Conversation;
        $conversation->sender_id = Auth::user()->id;
        $conversation->receiver_id = $request->user_id;
        $conversation->title = User::findOrFail($request->user_id)->email;
        $conversation->save();

        $message = new Message;
        $message->conversation_id = $conversation->id;
        $message->user_id = Auth::user()->id;
        $message->message = $request->message;
        $message->save();

        $user = User::find($request->user_id);
        $title = Auth::user()->name;
        $body = $message->message;
        if($user->device_token != null){
            $tokens = array();
            array_push($tokens,$user->device_token);  
            $push_controller = new PushNotificationController();
            $push_controller->sendNotification($title, $body, $tokens, '#');
        }

        flash(__('Message has been send to seller'))->success();
        return redirect()->route('admin.conversation.index');
    }

    public function send_offers(Request $request){
        $users = User::where('user_type','seller')->get();

        foreach($users as $user){
            $conversation = Conversation::where('receiver_id',$user->id)->where('sender_id',Auth::user()->id)->first();
            if($conversation){
                $message = new Message;
                $message->conversation_id = $conversation->id;
                $message->user_id = Auth::user()->id;
                $message->message = $request->message;
                $message->save();
            }else{
                $conversation = new Conversation;
                $conversation->sender_id = Auth::user()->id;
                $conversation->receiver_id = $user->id;
                $conversation->title = $user->email;
                $conversation->save();

                $message = new Message;
                $message->conversation_id = $conversation->id;
                $message->user_id = Auth::user()->id;
                $message->message = $request->message;
                $message->save();
            }
            
        }
        flash(__('Message has been send to sellers Successfully'))->success();
        return redirect()->back();
    }

    public function refresh(Request $request)
    {
        $conversation = Conversation::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail(decrypt($request->id));
        if($conversation->sender_id == Auth::user()->id){
            $conversation->sender_viewed = 1;
            $conversation->save();
        }
        else{
            $conversation->receiver_viewed = 1;
            $conversation->save();
        }
        return view('partials.messages', compact('conversation'));
    }

    public function index(Request $request,$my_conversation = 1){

        $conversations_ = Conversation::where('sender_id',auth()->user()->id)->get();
        $ids = [];
        foreach($conversations_ as $raw){
            $ids[] = $raw->receiver_id;
        }
        $users = User::whereNotIn('id',$ids)->whereIn('user_type',['seller','customer','designer'])->get();

        $users2 = User::whereIn('user_type',['seller','customer','designer'])->get();
        $staffs = User::whereNotIn('id',[auth()->user()->id])->whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();

        $sort_user = null ;

        $sort_staff = null;


        if($my_conversation)
            $conversations = Conversation::with(['receiver','sender'])->where('sender_id',auth()->user()->id);
        else
            $conversations = Conversation::with(['receiver','sender'])->whereNotIn('sender_id',[auth()->user()->id]);

        if($request->sort_user != null){
            $sort_user = $request->sort_user;
            $conversations = $conversations->where('receiver_id',$sort_user);
        }
        
        if($request->sort_staff != null){
            $sort_staff = $request->sort_staff;
            $conversations = $conversations->where('sender_id',$sort_staff);
        }

        $conversations = $conversations->orderBy('sender_viewed')->orderBy('updated_at', 'desc')->paginate(10);

        return view('admin.conversations.index', compact('conversations','my_conversation','sort_user','sort_staff','users2','staffs','users'));
    }

    
    public function show(Request $request,$id)
    {
        $conversation = Conversation::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail(decrypt($id));

        $start = 0 ;

        $limit = 30 ;
        
        if ($request->has('start')) {
            $start = $request->start;
        }

        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        $messages = Message::with('user')->where('conversation_id',decrypt($id))->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();

        if ($conversation->sender_id == Auth::user()->id) {
            $conversation->sender_viewed = 1;
        }
        elseif($conversation->receiver_id == Auth::user()->id) {
            $conversation->receiver_viewed = 1;
        }
        $conversation->save();

        if($request->ajax()){
            $output = '';
            foreach($messages as $message){
            
                if ($message->user_id != $conversation->receiver_id){
            $output .= '<div class="form-group">
                        <div class="media-body">
                            <p style="  float: right;
                                        background-color: #362458;
                                        color:white;
                                        width: fit-content;
                                        width: -moz-fit-content;
                                        padding: 15px;"" title="'. format_Date_time(strtotime($message->created_at)) .'">
                                '. nl2br($message->message) .'
                            </p>
                        </div>
                    </div>';
                }else{
            $output .= '<div class="form-group">
                        <div>
                            <div class="media-body">
                                <div style="clear: both"></div>
                                <p style="  background-color: #534c4c;
                                            width: fit-content;
                                            width: -moz-fit-content;
                                            padding: 15px;" title="'. format_Date_time(strtotime($message->created_at)) .'">
                                '. nl2br($message->message) .'
                                </p>
                            </div>
                        </div>
                    </div>';
                }
            }    
            return $output;
        }else{
            return view('admin.conversations.show', compact('conversation','messages'));
        }

    }
    
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail(decrypt($id));
        foreach ($conversation->messages as $key => $message) {
            $message->delete();
        }
        if(Conversation::destroy(decrypt($id))){
            flash(__('Conversation has been deleted successfully'))->success();
            return back();
        }
    }
}
