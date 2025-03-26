<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function PaymentLists()
    {
        return view('admin.backend.payment.payments');
    }
}