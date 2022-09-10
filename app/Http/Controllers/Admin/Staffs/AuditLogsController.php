<?php

namespace App\Http\Controllers\Admin\Staffs;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        $sort_logs = null;

        $audits = AuditLog::with('user');

        if($request->has('sort_logs')){
            $sort_logs = $request->sort_logs;
            $audits = $audits->where('subject_type',$sort_logs);
        }

        $audits = $audits->orderBy('created_at','desc')->paginate(20);

        return view('admin.staffs.auditLogs.index',compact('audits'));
    }

    public function show($id)
    {
        $auditLog = AuditLog::with('user')->find($id); 
        return view('admin.staffs.auditLogs.show', compact('auditLog'));
    }
}
