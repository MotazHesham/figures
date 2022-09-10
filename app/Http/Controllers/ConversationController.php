<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\BusinessSetting;
use App\Models\Message;
use App\Models\User;
use App\Models\Product;
use Auth;

class ConversationController extends Controller
{
    
    public function refresh(Request $request)
    {
        $conversation = Conversation::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }])->findOrFail(decrypt($request->id));
        if($conversation->sender_id == Auth::user()->id){
            $conversation->sender_viewed = 1;
            $conversation->save();
        }
        else{
            $conversation->receiver_viewed = 1;
            $conversation->save();
        }
        return view('frontend.partials.messages', compact('conversation'));
    }
    
    public function index()
    {
        if (BusinessSetting::where('type', 'conversation_system')->first()->value == 1) {
            $conversations = Conversation::where('sender_id', Auth::user()->id)->orWhere('receiver_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(5);
            return view('frontend.conversations.index', compact('conversations'));
        }
        else {
            flash(__('Conversation is disabled at this moment'))->warning();
            return back();
        }
    }

    public function show(Request $request,$id)
    {
        $conversation = Conversation::with(['messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail(decrypt($id));

        $start = 0 ;

        $limit = 10 ;
        
        if ($request->has('start2')) {
            $start = $request->start2;
        }

        if ($request->has('limit2')) {
            $limit = $request->limit2;
        }

        $messages = Message::with('user')->where('conversation_id',decrypt($id))->orderBy('created_at', 'desc')->offset($start)->limit($limit)->get();

        $messages = $messages->reverse();

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
            $output .= '<div class="block block-comment mb-3">
                            <div class="d-flex flex-row-reverse">
                                <div class="flex-grow-1 ml-5 pl-5">
                                    <div class="p-3 bg-gray rounded text-right" style="
                                    float: right;background-color: #a356ad;color: white; width:fit-content"  title="'. format_Date_time(strtotime($message->created_at)) .'">
                                        '.  nl2br($message->message) .' 
                                    </div>
                                </div>
                            </div>
                        </div>';
                }else{
            $output .= '<div class="block block-comment mb-3">
                            <div class="d-flex">
                                <div class="flex-grow-1 mr-5 pr-5">
                                    <div class="p-3 bg-gray rounded" style="    width: fit-content;
                                    
                                    " title="'. format_Date_time(strtotime($message->created_at)) .'">
                                        '.  nl2br($message->message) .' 
                                    </div>
                                </div>
                            </div>
                        </div>';
                }
            }    
            return $output;
        }else{
            return view('frontend.conversations.show', compact('conversation','messages'));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
