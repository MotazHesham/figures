<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BannedPhones;
use App\Http\Requests\BannedPhonesRequest;

class BannedPhonesController extends Controller
{
    protected $view = 'admin.settings.banned_phones.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banned_phones = BannedPhones::all();
        return view($this->view . 'index', compact('banned_phones'));
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
    public function store(BannedPhonesRequest $request)
    {
        BannedPhones::create($request->all());
        flash(__('inserted successfully'))->success();
        return redirect()->route('banned_phones.index');
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
        $phone = BannedPhones::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('phone'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannedPhonesRequest $request, $id)
    {
        $phone = BannedPhones::findOrFail($id);
        $phone->update($request->all());
        flash(__(' updated successfully'))->success();
        return redirect()->route('banned_phones.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(BannedPhones::destroy($id)){
            flash(__(' deleted successfully'))->success();
            return redirect()->route('banned_phones.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return redirect()->route('banned_phones.index');
        }
    }
}
