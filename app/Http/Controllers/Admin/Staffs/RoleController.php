<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    protected $view = 'admin.staffs.roles.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view($this->view . 'index', compact('roles'));
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
        if($request->has('permissions')){
            $role = new Role;
            $role->name = $request->name;
            $role->permissions = json_encode($request->permissions);
            if($role->save()){
                flash(__('Role has been inserted successfully'))->success();
                return redirect()->route('roles.index');
            }
        }
        flash(__('Something went wrong'))->error();
        return back();
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
        $role = Role::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('role'));
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
        $role = Role::findOrFail($id);

        if($request->has('permissions')){
            $role->name = $request->name;
            $role->permissions = json_encode($request->permissions);
            if($role->save()){
                flash(__('Role has been updated successfully'))->success();
                return redirect()->route('roles.index');
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
        if(Role::destroy($id)){
            flash(__('Role has been deleted successfully'))->success();
            return redirect()->route('roles.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return back();
        }
    }
}
