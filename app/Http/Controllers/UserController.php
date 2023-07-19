<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\JWTToken;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //User registration

    public function userRegistration(Request $request){
        try {


            User::create([
                'firstName'=>$request->input('firstName'),
                'lastName'=>$request->input('lastName'),
                'email'=>$request->input('email'),
                'mobile'=>$request->input('mobile'),
                'password'=>$request->input('password'),
            ]);

            return response()->json([
                "status"=>"Success",
                "Message"=>"Registration successfully"
            ], 200);

        }catch (\Exception $exception){
            return response()->json([
                "status"=>"Failed",
                "Message"=> "Registration Failed"
            ], 401);
        }
    }



    //User Login

    public function userLogin(Request $request){
       $user =  User::where('email', $request->input('email'))
            ->where('password', $request->input('password'))
            ->count();

        if(1 == $user){
            $token = JWTToken::createJWToken($request->input('email'));
            return response()->json([
                "status"=>"Success",
                "Message"=>"Registration successfully",
                "token"=>$token
            ], 200);

        }else{
            return response()->json([
                "status"=>"Failed",
                "Message"=> "Unauthorized"
            ], 401);
        }
    }

    //OTP generate

    public function SendOTPCode(Request $request){
            $email = $request->input('email');
            $result =   User::where('email', $email)->count();

            if(1 == $result){
                $otp = rand(1000, 9999);
                Mail::to($email)->send(new OTPMail($otp));
                User::where('email', $email)->update('otp', $otp);

                return response()->json([
                    "status"=>"Success",
                    "Message"=>"OTP Send successfully, Please check your mail",
                ], 200);

            }else{
                return response()->json([
                    "status"=>"Failed",
                    "Message"=> "Unauthorized"
                ], 401);
            }
    }
}
