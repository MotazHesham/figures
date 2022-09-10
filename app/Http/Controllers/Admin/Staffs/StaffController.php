<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Role;
use App\Models\User;
use Hash;
use App\Http\Requests\StoreStaffRequest;

class StaffController extends Controller
{
    protected $view = 'admin.staffs.staffs.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffs = Staff::all();
        return view($this->view . 'index', compact('staffs'));
    }

    public function update_show_notification(Request $request)
    {
        $user = User::findOrFail($request->id); 
        $user->notification_show = $request->status;
        $user->save(); 
        return 1; 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view($this->view . 'create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStaffRequest $request)
    {
        $validated_request = $request->all();

        $validated_request['user_type'] = "staff";
        $validated_request['password'] = bcrypt($request->password);
        $user = User::create($validated_request);
        
        if($user){
            $staff = new Staff;
            $staff->user_id = $user->id;
            $staff->role_id = $request->role_id;
            $staff->save();
        } 
        flash(__('Staff has been inserted successfully'))->success();
        return redirect()->route('staffs.index');
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
        $staff = Staff::findOrFail(decrypt($id));
        $roles = Role::all();
        return view($this->view . 'edit', compact('staff', 'roles'));
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
        $staff = Staff::findOrFail($id);
        $user = $staff->user;

        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email,'.$user->id,
            'password'=>'nullable|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'required',
            'role_id'=> 'required',
        ]); 
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        if($user->save()){
            $staff->role_id = $request->role_id;
            if($staff->save()){
                flash(__('Staff has been updated successfully'))->success();
                return redirect()->route('staffs.index');
            }
        }

        flash(__('Something went wrong'))->error();
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
        User::destroy(Staff::findOrFail($id)->user->id);
        if(Staff::destroy($id)){
            flash(__('Staff has been deleted successfully'))->success();
            return redirect()->route('staffs.index');
        }

        flash(__('Something went wrong'))->error();
        return back();
    }
}
