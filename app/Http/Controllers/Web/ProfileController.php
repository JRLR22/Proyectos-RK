<?php

namespace App\Http\Controllers\Web;

use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('profile', [
            'orders' => Order::forUser($user->user_id)->recent()->get(),
            'invoices' => Invoice::forUser($user->user_id)->recent()->get(),
            // luego direcciones 
        ]);
    }
}
