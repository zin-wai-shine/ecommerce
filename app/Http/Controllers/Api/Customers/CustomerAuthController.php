<?php

namespace App\Http\Controllers\Api\Customers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomClass\Detect;
use App\Http\Requests\CustomerForgotPasswordRequest;
use App\Http\Requests\CustomerLoginRequest;
use App\Http\Requests\CustomerRegisterRequest;
use App\Http\Resources\CustomerRegisterResource;
use App\Http\Resources\CustomerResource;
use App\Mail\CustomerForgotPasswordMail;
use App\Models\Customer;
use App\Models\CustomerDevice;
use App\Services\Auth\Customer\CustomerDeviceService;
use App\Traits\UsePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\PersonalAccessToken;

class
CustomerAuthController extends Controller
{

    use UsePhoto;

    public function customer_login(CustomerLoginRequest $request){

        $customerValidate = $request->validated();
        $userAgent = $request->header('User-Agent');
        $credentials = $request->only(['email','password']);

        if(auth('customer')->attempt($credentials)){
            $customer = Customer::where('email', $customerValidate['email'])->first();
            $customer->id = $customer->customer_id;

            /*
            why added "id" in the customer? why well when use createToken that catch the
            customer id and assign to the "tokenable_id" in the personal_access_tokens table.
            In their we were customized the customer id "id" to "customer_id".So, need to add "id" for createToken....
            */
            $token = $customer->createToken($customer->full_name.'_customer_login_token')->plainTextToken;

            /*$token = Str::random(60);
            return response()->json($token);*/
            if(isset($token)){
                CustomerDeviceService::create($request,$userAgent,$customer,$token,'login');

                return response()->json([
                    'token' => $token,
                    'status' => 'success',
                    'message' => 'Login successful.',

                ], 200);
            }


        } else {
            return response()->json([
                'message' => 'Login failed.',
            ], 401);
        }

    }

    public function customer_register(CustomerRegisterRequest $request){

        /*get the customer device info*/
        $userAgent = $request->header('User-Agent');

        /*add customer register info into database....need permission to $fillable=[] in Model....
        In their we were created data not get data directly in $request get in validated() in CustomerRegisterRequest
        that was great for validation...*/
        $customerValidate = $request->validated();
        $customer = Customer::create([
            'first_name' => $customerValidate['first_name'],
            'last_name' => $customerValidate['last_name'],
            'full_name' => $customerValidate['first_name'].' '.$customerValidate['last_name'],
            'email' => $customerValidate['email'],
            'password' => Hash::make($request->password)

        ]);

        /*In their we can use the same way... Auth::gurad('customer')->attempt($request->only(['email', 'password']));*/
        $credentials = $request->only(['email', 'password']);
        if (auth('customer')->attempt($credentials)){

            $token = $customer->createToken('customer_register_token')->plainTextToken;

            if(isset($token)){
                CustomerDevice::create([
                    'action_type'=>'Register',
                    'device' =>Detect::getDeviceType($userAgent) .' / '.Detect::browser() . ' / ' . implode(" / ",Detect::systemInfo()),
                    'date'=> now(),
                    'ip'=> $request->ip(),
                    'customer_id'=>$customer->customer_id
                ]);

                return response()->json([
                    'token' => $token,
                    'status' => 'success',
                    'message' => 'Registration successful.',
                    'customer' => new CustomerRegisterResource($customer)
                ], 200);
            }

        } else {
            return response()->json([
                'message' => 'Registration failed.',
            ], 401);

        }

    }

    public function customer_forgot_password(CustomerForgotPasswordRequest $request){
        $resetCode = random_int(100000,999999);
        Mail::to($request->email)->send(new CustomerForgotPasswordMail($resetCode,$request->email));
        $customerInfo = Customer::where('email',$request->email)->first();
        $customer = new CustomerResource($customerInfo);
        return response()->json([
            "status" => true,
            "reset_code" => $resetCode,
            "message" => "reset was send",
            "customer" => $customer
        ]);
    }

    public function customer_reset_password(Request $request){
        $request->validate([
           'new_password' => 'required',
           'customer_id' => 'required'
        ]);
        $customer = Customer::find($request->customer_id);
        if(!$customer){
            return response()->json(["message" => "user not found"]);
        }
        $customer->password = Hash::make($request->new_password);
        $customer->update();
        return response()->json([
            "status" => true,
            "message" => "reset password successful"
        ]);
    }

    public function customer_logout(){
        Auth::user()->currentAccessToken()->delete();
        return response()->json(["status" => true, "message" => "Logout Successfully"]);
    }

    public function customer_logout_all(){
       Auth::user()->tokens()->delete();
       return response()->json(["status" => true, "message" => "Logout all customer successful"]);
    }

    public function customer_logout_all_except_current(){
        $currentAccessToken = auth()->user()->currentAccessToken();
        $customer = auth()->user();

        $customerTokens = PersonalAccessToken::where('tokenable_id', $customer->customer_id)
            ->where('id', '<>', $currentAccessToken->id)
            ->get();

        foreach ($customerTokens as $token) {
            if ($token->id !== $currentAccessToken->id) {
                DB::table('personal_access_tokens')->where('id', $token->id)->delete();
            }
        }
        return response()->json(["status" => true, "message" => "logout all customer except current login user successful"]);
    }

    public function tokens(){
        $tokens = Auth::user()->tokens;
        return response()->json(["status" => true, "tokens" => $tokens]);
    }

}
