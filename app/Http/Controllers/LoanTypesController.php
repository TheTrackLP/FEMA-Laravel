<?php

namespace App\Http\Controllers;

use App\Models\LoanTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoanTypesController extends Controller
{
    public function LoanTypesDashboard(){
        return inertia('Admin/Backend/LoanTypes', [
            'types'=>LoanTypes::all(),
        ]);
    }

    public function LoanTypesStore(Request $request) {
        $valid = Validator::make($request->all(), [
            'name' => '',
            'desc' => '',
            'interest_rate' => '',
            'penalty' => '',
            'isActive' => '',
        ]);

        if($valid->fails()){
            return redirect()->route('types.dash')->with(
                'error', 'Try Again!',
            );
        }

        LoanTypes::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'interest_rate' => $request->interest_rate,
            'penalty' => $request->penalty,
            'isActive' => $request->isActive,
        ]);

        return redirect()->route('types.dash')->with(
            'success', 'Loan Type Added!',
        );
    }

    public function LoanTypesUpdate(Request $request){
         $valid = Validator::make($request->all(), [
            'name' => '',
            'desc' => '',
            'interest_rate' => '',
            'penalty' => '',
            'isActive' => '',
        ]);

        if($valid->fails()){
            return redirect()->route('types.dash')->with(
                'error', 'Try Again!',
            );
        }

        LoanTypes::findorfail($request->id)->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'interest_rate' => $request->interest_rate,
            'penalty' => $request->penalty,
            'isActive' => $request->isActive,
        ]);

        return redirect()->route('types.dash')->with(
            'success', 'Loan Type Update!',
        );
    }

    public function LoanTypesStatus($id){
        $type = LoanTypes::findorfail($id);

        if($type->isActive == 1){
            $type->update([
                'isActive' => 0,
            ]);
        } elseif($type->isActive == 0){
            $type->update([
                'isActive' => 1,
            ]);
        }

        return redirect()->route('types.dash')->with(
            'success', 'Loan Type Status Changed!',
        );
    }
}
