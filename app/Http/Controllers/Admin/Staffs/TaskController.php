<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\UserAlert; 
use App\Http\Controllers\PushNotificationController;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Config;

class TaskController extends Controller
{

    protected $view = 'admin.staffs.tasks.';

    public function update_done(Request $request)
    {
        $task = Task::findOrFail($request->id);
        $task->done = 1;
        $task->done_time = strtotime(Carbon::now());
        if($task->save()){
            flash(__('Task Done'))->success();
            return back();
        }
        flash(__('something went wrong'))->error();
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $staffs = User::where('user_type','staff')->get();
        $done = Task::where('done',1)->get();
        $in_progress = count(Task::where('done',0)->get());

        $out_time = 0;
        $done_count = 0;
        foreach($done as $raw){
            if($raw->done_time > $raw->end){
                $out_time++;
            }else{
                $done_count++;
            }
        }

        $sort_staff = null;

        if($request->sort_staff != null){
            $sort_staff = $request->sort_staff;
            $tasks = Task::where('user_id',$sort_staff)->orderBy('created_at','desc')->paginate(10);
            return view($this->view . "index", compact('tasks','in_progress','done_count','out_time','staffs'));
        }else{
            $tasks = Task::orderBy('created_at','desc')->paginate(10);
            return view($this->view . "index", compact('tasks','in_progress','done_count','out_time','staffs'));
        }

        


    }

    public function in_progress()
    {
        $tasks = Task::where('user_id',Auth::user()->id)->where('done',0)->orderBy('created_at','desc')->paginate(10);

        $done = Task::where('user_id',Auth::user()->id)->where('done',1)->get();
        $in_progress = count(Task::where('user_id',Auth::user()->id)->where('done',0)->get());

        $out_time = 0;
        $done_count = 0;
        foreach($done as $raw){
            if($raw->done_time > $raw->end){
                $out_time++;
            }else{
                $done_count++;
            }
        }
        return view($this->view . "in_progress", compact('tasks','in_progress','done_count','out_time'));
    }
    public function done()
    {
        $tasks = Task::where('user_id',Auth::user()->id)->where('done',1)->whereColumn([
            ['done_time', '<', 'end']
        ])->orderBy('created_at','desc')->paginate(10);

        $done = Task::where('user_id',Auth::user()->id)->where('done',1)->get();
        $in_progress = count(Task::where('user_id',Auth::user()->id)->where('done',0)->get());

        $out_time = 0;
        $done_count = 0;
        foreach($done as $raw){
            if($raw->done_time > $raw->end){
                $out_time++;
            }else{
                $done_count++;
            }
        }
        return view($this->view . "done", compact('tasks','in_progress','done_count','out_time'));
    }
    public function out_date()
    {
        $tasks = Task::where('user_id',Auth::user()->id)->where('done',1)->whereColumn([
            ['done_time', '>', 'end']
        ])->orderBy('created_at','desc')->paginate(10);

        $done = Task::where('user_id',Auth::user()->id)->where('done',1)->get();
        $in_progress = count(Task::where('user_id',Auth::user()->id)->where('done',0)->get());

        $out_time = 0;
        $done_count = 0;
        foreach($done as $raw){
            if($raw->done_time > $raw->end){
                $out_time++;
            }else{
                $done_count++;
            }
        }
        return view($this->view . "out_date", compact('tasks','in_progress','done_count','out_time'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $staffs = User::where('user_type','staff')->get();
        return view($this->view . 'create',compact('staffs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     
     public function sendEmail($mailText, $email, $subject, $fileNameToStore = null)
    {
        $fromName = "Ebtekar Store";
        $fromAddress = "pusher@ebtekarstore.net";
        $fromPass = "ebtekars000";
        $fromDriver = "smtp";
        $fromHost = "ebtekarstore.net";
        $fromPort = "587";
        $fromType = "tls";

        if ($fromDriver && $fromAddress) {
            Config::set('mail.username', $fromAddress);
            Config::set('mail.password', $fromPass);
            Config::set('mail.host', $fromHost);
            Config::set('mail.driver', $fromDriver);
            Config::set('mail.port', $fromPort);
            Config::set('mail.encryption', $fromType);
            Config::set('mail.from.address', $fromAddress);
            Config::set('mail.from.name', $fromName);

            if ($fileNameToStore != null) {
                $pdf = public_path('uploads/pdf/' . $fileNameToStore);
                Mail::send([], [], function ($message) use ($email, $subject, $mailText, $pdf) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html')->Attach(\Swift_Attachment::fromPath($pdf));
                });
            } else {
                Mail::send([], [], function ($message) use ($email, $subject, $mailText) {
                    $message->to($email)->subject($subject)->setBody($mailText, 'text/html');
                });
            }


            return true;
        } else {
            return false;
        }
    }
     
     
     
    public function store(Request $request)
    {
        
        $task = new task;
        $task->user_id = $request->staff;
        $task->task = $request->task;
        $task->title = $request->title;
        $task->start = strtotime($request->start_date);
        $task->end = strtotime($request->end_date);
        $task->save();

        $title = 'لديك مهمة جديدة';
        $body = $task->title;
        UserAlert::create([
            'alert_text' => $title . ' ' . $body,
            'alert_link' => route('tasks.in_progress'),
            'type' => 'private',
            'user_id' => $task->user_id ,
        ]);
        
        $user = User::find($task->user_id);
        if($user->device_token != null){
            $tokens = array();
            array_push($tokens,$user->device_token);  
            $push_controller = new PushNotificationController();
            $push_controller->sendNotification($title, $body, $tokens,route('tasks.in_progress'));
        }

        flash('تم الأضافة بنجاح')->success();
        return redirect()->route('tasks.index');
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
        $task = Task::findOrFail(decrypt($id));
        $staffs = User::where('user_type','staff')->get();
        return view($this->view . 'edit', compact('task','staffs'));
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
        $task = Task::findOrFail($id);
        $task->user_id = $request->staff;
        $task->task = $request->task;
        $task->title = $request->title;
        if($request->start_date != null){
            $task->start = strtotime($request->start_date);
        }
        if($request->end_date != null){
            $task->end = strtotime($request->end_date);
        }
        if($task->save()){
            flash('task has been updated successfully')->success();
            return redirect()->route('tasks.index');
        }
        flash('Something went wrong')->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        if(Task::destroy($id)){
            flash('task has been deleted successfully')->success();
            return redirect()->route('tasks.index');
        }

        flash('Something went wrong')->error();
        return back();
    }
}
