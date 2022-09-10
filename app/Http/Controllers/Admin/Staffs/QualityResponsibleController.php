<?php

namespace App\Http\Controllers\Admin\Staffs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QualityResponsible;

class QualityResponsibleController extends Controller
{
    public function show(){
        $quality_responsible = QualityResponsible::first();
        return view('admin.staffs.quality_responsible.edit',compact('quality_responsible'));
    }

    public function update(Request $request, $id){
        
        $quality_responsible = QualityResponsible::find($id);
        $quality_responsible->name = $request->name;
        $quality_responsible->phone = $request->phone;
        $quality_responsible->country_code = $request->country_code;
        $quality_responsible->wts_phone = $request->wts_phone;
        
        if($request->hasFile('photo')){ 
            $quality_responsible->photo = $request->photo->store('uploads/quality_responsible');
        }

        $quality_responsible->save();

        
        flash(__('Quality Responsible Updated Successfully'))->success();
        return redirect()->route('quality_responsible.show');

    }
}
