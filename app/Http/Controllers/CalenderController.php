<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calender;
use App\Models\User;
use App\Models\CalenderExport;
use Auth;
use DateTime;
use Carbon\Carbon;
use Validator;
use Excel;
use Hash;

class CalenderController extends Controller
{   

    public function calenderhome(){
        return view('frontend.calender_home');
    }
    

    public function export(Request $request){
        
        $from = strtotime($request->from);
        $to = strtotime($request->to);

        return Excel::download(new CalenderExport($from,$to), 'calender.xlsx');
    }

    public function admin_index(Request $request){
        $sort_search = null;
        $date = null;

        $users = User::whereIn('user_type',['seller','customer','designer'])->whereHas('calenders');
        
        if ($request->has('search') && $request->search != null){
            $sort_search = $request->search;
            $users = $users->where('phone', 'like', '%'.$sort_search.'%');
        }
        if ($request->has('date') && $request->date != null){
            global $date;
            $date = $request->date;
            $users = $users->with(['calenders' => function ($query) {
                $query->where('date',strtotime($GLOBALS['date']))->orderBy('date', 'desc');
            }]);
        }

        $users = $users->paginate(9);

        return view('admin.calender.index',compact('users','sort_search','date'));
    }
    
    public function delete_calender($id){
        $calender = Calender::find($id);
        
        if($calender->delete()){
            flash(__('Ocasoion Deleted'))->success();
            return redirect()->back();
        }
            flash(__('some thing went wrong'))->error();
            return redirect()->back();
    }
    public function delete_calender_user($id){
        $calender = CalenderUser::find($id);
        
        if($calender->delete()){
            flash(__('User Deleted'))->success();
            return redirect()->back();
        }
            flash(__('some thing went wrong'))->error();
            return redirect()->back();
    }

    public function calender_by_date($date){
        $calender = Calender::where('date',strtotime($date))->orderBy('date', 'desc')->paginate(10);
        return view('calender.index',compact('calender'));
    }
    
    public function index(Request $request){
        $calender = Calender::where('user_id',Auth::id())->get();
        $calender2 = Calender::where('user_id',Auth::id())->paginate(4);
        $id = Auth::id();
        $calender = collect($calender);
        
        

        $myEvents = $calender->transform(function ($value,$key){
            

            $data=[];
            $data['id'] = 'required-id-'.$value['id'];
            $data['name'] = $value['title'];

            $data['date'] = date('m-d-Y',$value['date']);
            $data['type'] = $value['type'];
            if($value['everyYear'] == 1){
                $data['everyYear'] = true;
            }else{
                $data['everyYear'] = false;
            }
            $data['color'] = $value['color'];
            $data['description'] = $value['description'];

            return $data;
        });
        
        return view('frontend.calender',compact('calender2','myEvents','id'));
    }

    public function addevent(Request $request){
        $this->validate($request,[
            'title'=>'required|string|max:200',
            'description'=>'required|string|max:200',
        ]);

        $calender = new Calender;
        $calender->user_id = Auth::id();
        $calender->title = $request->title;
        $calender->description = $request->description;
        $calender->date = strtotime($request->date);
        $calender->save();

        flash(__('Event Added Successfully'))->success();
        return redirect()->route('calender');
    }
    
    public function delete($id){
        if(Calender::destroy($id)){
            flash(__('Event deleted'))->success();
            return redirect()->route('calender');
        }else{
            flash(__('some thing went wrong'))->error();
            return redirect()->route('calender');
        }
    }
}
