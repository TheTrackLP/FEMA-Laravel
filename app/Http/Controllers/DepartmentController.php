<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\Departments;
use Carbon\Carbon;

class DepartmentController extends Controller
{
    public function DepartmentsLists()
    {
        $depts = Departments::all();
        return view('admin.backend.dept.departments', compact('depts'));
    }

    public function DepartmentStore(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'dept_name' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('dept.list')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error',
                            ]);
        }

        Departments::create($request->all());

        return redirect()->route('dept.list')
                        ->with([
                            'message' => 'Department Added Successfully',
                            'alert-type' => 'success'
                        ]);
    }

    public function DepartmentEdit($id)
    {
        $depts = Departments::findorfail($id);
        return response()->json([
            'status'=>200,
            'dept'=>$depts,
        ]);
    }

    public function DepartmentUpdate(Request $request)
    {
        $dept_id = $request->id;
        $valid = Validator::make($request->all(), [
            'dept_name' => 'required',
        ]);

        if ($valid->fails()) {
            return redirect()->route('dept.list')
                             ->withErrors($valid)
                             ->with([
                                'message' => 'Error, Try Again!',
                                'alert-type' => 'error',
                             ]);
        }

        Departments::findorfail($dept_id)->update([
            'dept_name' => $request->dept_name,
            'updated_at' => Carbon::now(),
        ]);

        return redirect()->route('dept.list')
                        ->with([
                            'message' => 'Department Update Successfully',
                            'alert-type' => 'success'
                        ]);
    }

    public function DepartmentDelete($id)
    {
        Departments::findorfail($id)->delete();
        return redirect()->route('dept.list')
                       ->with([
                        'message' => 'Department Delete Successfully',
                        'alert-type' => 'warning',
                    ]);   
    }
}