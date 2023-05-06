<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\customer\CustomerUpdateRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\Auth\Customer\CustomerService;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerService;
    public function __construct(CustomerService $customerService){
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = Customer::search()->latest('customer_id')->get();
        return response()->json(['success'=>true, 'customers' => CustomerResource::collection($customers)]);
    }

    /*public function deleted_customers(){
        $customers = Customer::where('deleted_flag', '1')->get();
        return $customers;
    }*/

    public function show($id)
    {
        return $this->customerService->find($id, "", "", true);
    }

    public function update(CustomerUpdateRequest $request, $id)
    {

        $customer = Customer::find($id);

        if($request->hasFile('profile_photo')){
            $fileName = uniqid().''.$request->file('profile_photo')->getClientOriginalName();
            $request->file('profile_photo')->storeAs('/public/customer/profile', $fileName);
        } else{
            $fileName = $customer->profile_photo;
        }
        $customer->profile_photo = $fileName;

        if($request->first_name){
            $customer->first_name = $request->first_name;
            $customer->full_name = ($request->first_name). ' ' .($customer->last_name);
        }

        if($request->last_name){
            $customer->last_name = $request->last_name;
            $customer->full_name = ($customer->first_name). ' ' .($request->last_name);
        }

        $customer->email = $request->email;
        $customer->phone_number = $request->phone_number;
        $customer->delivery_address = $request->delivery_address;

        $customer->update();

        return response()->json(['success' => true, 'message' => 'information is updated']);

    }

    public function force_delete($id){
        $message = 'customer was deleted';
        return $this->customerService->find($id, "1", $message);
    }

    public function restore($id){
        $message = 'customer was restored';
        return $this->customerService->find($id, "0", $message);
    }

    public function destroy($id)
    {
        return $this->customerService->delete($id);
    }

   
}
