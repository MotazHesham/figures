<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use ImageOptimizer;

class GeneralSettingController extends Controller
{
    protected $view = 'admin.settings.general_settings.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $generalsetting = GeneralSetting::first(); 
        $staffs = User::whereIn('user_type',['staff','admin'])->where('email','!=','wezaa@gmail.com')->get();
        return view($this->view . "index", compact("generalsetting",'staffs'));
    }

    public function logo()
    {
        $generalsetting = GeneralSetting::first();
        return view($this->view . "logo", compact("generalsetting"));
    }

    //updates the logo and favicons of the system
    public function storeLogo(Request $request)
    {
        $generalsetting = GeneralSetting::first();

        if($request->hasFile('logo')){
            $generalsetting->logo = $request->file('logo')->store('uploads/logo');
            //ImageOptimizer::optimize(base_path('public/').$generalsetting->logo);
        }

        if($request->hasFile('admin_logo')){
            $generalsetting->admin_logo = $request->file('admin_logo')->store('uploads/admin_logo');
            //ImageOptimizer::optimize(base_path('public/').$generalsetting->admin_logo);
        }

        if($request->hasFile('favicon')){
            $generalsetting->favicon = $request->file('favicon')->store('uploads/favicon');
            //ImageOptimizer::optimize(base_path('public/').$generalsetting->favicon);
        }

        if($request->hasFile('admin_login_background')){
            $generalsetting->admin_login_background = $request->file('admin_login_background')->store('uploads/admin_login_background');
            //ImageOptimizer::optimize(base_path('public/').$generalsetting->admin_login_background);
        }

        if($request->hasFile('admin_login_sidebar')){
            $generalsetting->admin_login_sidebar = $request->file('admin_login_sidebar')->store('uploads/admin_login_sidebar');
            //ImageOptimizer::optimize(base_path('public/').$generalsetting->admin_login_sidebar);
        }


        if($generalsetting->save()){
            flash('Logo settings has been updated successfully')->success();
            return redirect()->route('generalsettings.logo');
        }
        else{
            flash('Something went wrong')->error();
            return back();
        }
    }

    public function color()
    {
        $generalsetting = GeneralSetting::first();
        return view($this->view . "color", compact("generalsetting"));
    }

    //updates system ui color
    public function storeColor(Request $request)
    {
        $generalsetting = GeneralSetting::first();
        $generalsetting->frontend_color = $request->frontend_color;

        if($generalsetting->save()){
            flash('Color settings has been updated successfully')->success();
            return redirect()->route('generalsettings.color');
        }
        else{
            flash('Something went wrong')->error();
            return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    public function receipt_colors(Request $request){ 
        $generalsetting = GeneralSetting::first();
        $receipt_colors = array();
        $receipt_colors = [
            'date_created' => $request->date_created,
            'date_of_receiving_order' => $request->date_of_receiving_order,
            'delivery_date' => $request->delivery_date,
            'order_cost' => $request->order_cost,
            'total_cost' => $request->total_cost,
            'deposit' => $request->deposit,
            'shipping_country_cost' => $request->shipping_country_cost,
            'added_by' => $request->added_by,
            'delivery_man' => $request->delivery_man, 
            'receipt_type' => $request->receipt_type, 
            'quickly_1' => $request->quickly_1, 
            'quickly_2' => $request->quickly_2, 
            'quickly_3' => $request->quickly_3, 
            'done_1' => $request->done_1, 
            'done_2' => $request->done_2, 
            'done_3' => $request->done_3, 
            'no_answer_1' => $request->no_answer_1, 
            'no_answer_2' => $request->no_answer_2, 
            'no_answer_3' => $request->no_answer_3, 
        ];
        $generalsetting->receipt_colors = json_encode($receipt_colors);
        $generalsetting->save();
        flash('GeneralSetting has been updated successfully')->success();
        return back();
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
        $generalsetting = GeneralSetting::first();
        $generalsetting->site_name = $request->name;
        $generalsetting->address = $request->address;
        $generalsetting->phone = $request->phone;
        $generalsetting->email = $request->email;
        $generalsetting->description = $request->description;
        $generalsetting->facebook = $request->facebook;
        $generalsetting->instagram = $request->instagram;
        $generalsetting->twitter = $request->twitter;
        $generalsetting->youtube = $request->youtube;
        $generalsetting->google_plus = $request->google_plus;
        $generalsetting->telegram = $request->telegram;
        $generalsetting->linkedin = $request->linkedin;
        $generalsetting->whatsapp = $request->whatsapp;
        $generalsetting->welcome_message = $request->welcome_message;
        $generalsetting->video_instructions = $request->video_instructions;
        $generalsetting->admin_sidenav = $request->admin_sidenav;
        $generalsetting->delivery_system = $request->delivery_system;
        $generalsetting->date = $request->date;
        $generalsetting->time = $request->time;
        $generalsetting->designer_id = $request->designer_id;
        $generalsetting->manifacturer_id = $request->manifacturer_id;
        $generalsetting->preparer_id = $request->preparer_id;

    

        if($request->has('previous_photos')){
            $photos = $request->previous_photos;
        }
        else{
            $photos = array();
        }

        if($request->hasFile('photos')){
            foreach ($request->photos as $key => $photo) {
                $path = $photo->store('uploads/client_opinion');
                array_push($photos, $path);
                //ImageOptimizer::optimize(base_path('public/').$path);
            }
        }
        $generalsetting->photos = json_encode($photos); 
        $generalsetting->save();

        flash('GeneralSetting has been updated successfully')->success();
        return redirect()->route('generalsettings.index'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
