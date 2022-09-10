<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Social;
use App\Http\Requests\SocialsNetworkRequest;

class SocialsNetworkController extends Controller
{
    protected $view = 'admin.settings.social.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $socials = Social::all();
        return view($this->view . 'index', compact('socials'));
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
    public function store(SocialsNetworkRequest $request)
    {
        $validated_request = $request->all();

        if($request->hasFile('photo')){
            $validated_request['photo'] = $request->file('photo')->store('uploads/socials');
        }
        $social = Social::create($validated_request);
        flash(__('Social has been inserted successfully'))->success();
        return redirect()->route('social.index');
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
        $social = Social::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('social'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SocialsNetworkRequest $request, $id)
    {
        $social = Social::findOrFail($id);
        $validated_request = $request->all();

        if($request->hasFile('photo')){
            $validated_request['photo'] = $request->file('photo')->store('uploads/socials');
        }
        $social->update($validated_request);
        flash(__('Social has been updated successfully'))->success();
        return redirect()->route('social.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Social::destroy($id)){
            flash(__('Social has been deleted successfully'))->success();
            return redirect()->route('social.index');
        }
        else{
            flash(__('Something went wrong'))->error();
            return redirect()->route('social.index');
        }
    }
}
