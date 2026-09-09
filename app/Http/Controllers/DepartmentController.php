<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    public function DepartmentDashboard(){
        return inertia('Admin/Backend/Department', [
            'depts'=>Departments::all(),
        ]);
    }

    public function DepartmentStore(Request $request){
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'acronym' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('dept.dash')->with(
                'error', 'Try Again!',
            );
        }
            
        Departments::create([
            'name' => $request->name,
            'code' => $request->code,
            'acronym' => $request->acronym,
        ]);

        return redirect()->route('dept.dash')->with(
            'success', 'Success, Department Added!',
        );
    }

    public function DepartmentEdit(Request $request){
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'acronym' => 'required',
        ]);

        if($valid->fails()){
            return redirect()->route('dept.dash')->with(
                'error', 'Try Again!',
            );
        }
            
        Departments::findorfail($request->id)->update([
            'name' => $request->name,
            'code' => $request->code,
            'acronym' => $request->acronym,
        ]);

        return redirect()->route('dept.dash')->with(
            'success', 'Success, Department Updated!',
        );
    }
}
