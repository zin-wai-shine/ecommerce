<?php

namespace App\Services\Auth\Customer;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Traits\ResponseJson;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;

class CustomerService
{

    use ResponseJson;
    public function find($id, $deleted_flag = "", $message = "", $hasCustomer = false): JsonResponse
    {

        $result = $this->findCustomer($id);

        if($result instanceof JsonResponse){
            return $result;
        }

        $customer = $result;

        if($hasCustomer === false){
            $customer->deleted_flag = "1";
            $customer->update();
            return response()->json(['success' => true, 'message' => $message]);
        }

        return response()->json(['success' => true, 'customer' => new CustomerResource($customer)]);

    }

    public function delete($id): JsonResponse
    {
        $customer = $this->findCustomer($id);
        $customer->delete();
        return response()->json(['success'=>true, 'message' => 'customer was deleted']);
    }

    private function findCustomer($id):Customer|JsonResponse
    {
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response()->json(['success' => false, 'message' => 'user not found']);
        }
        return $customer;
    }

}
