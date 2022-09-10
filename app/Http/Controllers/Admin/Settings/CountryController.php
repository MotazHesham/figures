<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    protected $view = 'admin.settings.countries.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $countries = Country::paginate(15);
        $type = null;
        $search = null;

        $countries = Country::orderBy('created_at','desc');
        if ($request->search != null){
            $countries = $countries->where('name','like','%'.$request->search.'%');
            $search = $request->search;
        }

        if ($request->type != null){
            $countries = $countries->where('type',$request->type);
            $type = $request->type;
        }

        $countries = $countries->paginate(15);

        return view($this->view . 'index', compact('countries','type','search'));
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
        $country = new Country;
        $country->name = $request->name;
        $country->cost = $request->cost;
        $country->type = $request->type;
        if($country->save()){
            flash('Country has been inserted successfully')->success();
            return redirect()->route('countries.index');
        }
        flash('Something went wrong')->error();
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
        $country = Country::findOrFail(decrypt($id));
        return view($this->view . 'edit', compact('country'));
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
        $country = Country::findOrFail($id);
        $country->name = $request->name;
        $country->cost = $request->cost;
        $country->type = $request->type;
        if($country->save()){
            flash('Country has been updated successfully')->success();
            return redirect()->route('countries.index');
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
        $country = Country::findOrFail($id);
        if(Country::destroy($id)){
            flash('country has been deleted successfully')->success();
            return redirect()->route('countries.index');
        }

        flash('Something went wrong')->error();
        return back();
    }

    public function updateStatus(Request $request){
        $country = Country::findOrFail($request->id);
        $country->status = $request->status;
        if($country->save()){
            return 1;
        }
        return 0;
    }
}
