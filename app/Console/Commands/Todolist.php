<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserAlert; 
use App\Models\User; 
use App\Models\Task; 
use App\Http\Controllers\PushNotificationController;
use Auth;

class Todolist extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todolist:alert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'alert users before task end';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $title = 'Ebtekar Tasks';
        $body = 'لديك مهام ليم يتم انهائها'; 

        $users_ids = Task::where('done',0)->get()->pluck('user_id');

        $tokens = User::whereIn('id',$users_ids)->whereNotNull('device_token')->get()->pluck('device_token'); 

        $push_controller = new PushNotificationController();
        $push_controller->sendNotification($title, $body, $tokens,'#'); 
    }
}
