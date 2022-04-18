<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Mails;
use App\Models\Personals;
use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        if(!LaravelGmail::check())
        {
            return redirect('/oauth/gmail');
        }
        $this->access_token = LaravelGmail::getToken()['access_token'];
        $this->url = "https://www.googleapis.com/gmail/v1/users/me/";
        $this->user = LaravelGmail::user();
        $this->personal_id = Personals::where('mail', $this->user)->first()->id;
        
    }
    public function index(){
        $customers = Customers::where('personal_id', $this->personal_id)->get();
        return view('customers', compact('customers'));
    }
    public function show($customer_id){
        $customer = Customers::where('id', $customer_id)->first();
        $mails = Mails::where('customer_id', $customer_id)->get();
        return view('customers', compact('customer', 'mails'));
    }
}
