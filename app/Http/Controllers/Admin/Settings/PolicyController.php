<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Policy;

class PolicyController extends Controller
{


    public function index($type)
    {
        $policy = Policy::where('name', $type)->first();
        return view('admin.settings.policies.index', compact('policy'));
    }

    //updates the policy pages
    public function store(Request $request){
        $policy = Policy::where('name', $request->name)->first();
        $policy->name = $request->name;
        $policy->content = $request->content;
        $policy->save();

        flash($request->name.' updated successfully');
        return back();
    }
}
