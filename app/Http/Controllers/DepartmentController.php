<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function DepartmentsLists()
    {
        return view('admin.backend.dept.departments');
    }
}