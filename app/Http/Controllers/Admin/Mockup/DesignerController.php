<?php

namespace App\Http\Controllers\Admin\Mockup;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\Role;
use App\Models\User;
use Hash; 

class DesignerController extends Controller
{
    protected $view = 'admin.mockups.designers.';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designers = User::where('user_type','designer')->get();
        return view($this->view . 'index', compact('designers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        return view($this->view . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_request = $request->all();

        $validated_request['user_type'] = "designer";
        $validated_request['password'] = bcrypt($request->password);
        $user = User::create($validated_request); 
        flash(__('Designer has been inserted successfully'))->success();
        return redirect()->route('designers.index');
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
        $user = User::findOrFail(decrypt($id)); 
        return view($this->view . 'edit', compact('user'));
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
        $user = User::findOrFail($id); 

        $this->validate($request,[
            'name'=>'required|min:3|max:50',
            'email'=>'required|min:3|max:50|email|unique:users,email,'.$user->id,
            'password'=>'nullable|min:6|confirmed',
            'phone' => 'required|regex:/(01)[0-9]{9}/|size:11',
            'address' => 'required', 
        ]); 
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->store_name = $request->store_name;
        if(strlen($request->password) > 0){
            $user->password = Hash::make($request->password);
        }
        $user->save();

        flash(__('Success'))->success();
        return redirect()->route('designers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        flash(__('Desgigner has been deleted successfully'))->success();
        return back();
    }
}
