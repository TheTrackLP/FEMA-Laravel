<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function AllACcounts()
    {
        return view('admin.backend.accts.accounts');
    }
}
