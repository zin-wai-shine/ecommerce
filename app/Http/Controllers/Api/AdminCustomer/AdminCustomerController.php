<?php

namespace App\Http\Controllers\Api\AdminCustomer;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\Auth\Customer\CustomerService;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::search()->latest('customer_id')->get();
        return response()->json(['success'=>true, 'customers' => CustomerResource::collection($customers)]);
    }

    public function show($id)
    {
        return (new CustomerService)->find($id, "", "", true);
    }


}
