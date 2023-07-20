<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helper\JWTToken;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{

    public function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }


    public function LoginPage():View{
        return view('pages.auth.login-page');
    }

    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }


    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }


    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }


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



    public function verifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $result = User::where('email', $email)->where('otp', $otp)->count();

        if($result == 1){
            User::where('email', $email)->update(['otp', '0']);
            $token = JWTToken::createJWTokenForPasswordReset($request->input('email'));
            return response()->json([
                "status"=>"Success",
                "Message"=>"OTP verify successfully",
                "token"=>$token
            ], 200);

        }else{
            return response()->json([
                "status"=>"Failed",
                "Message"=> "Unauthorized"
            ], 401);
        }
    }


    public function resetPassword(Request $request){
        $email = $request->header('email');
        $password = $request->input('password');


        try {

            User::where('email', "=" ,$email)
            ->update(['password'=> $password]);

            return response()->json([
                "status"=>"Success",
                "Message"=>"password reset successfully",
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status"=>"Failed",
                "Message"=> $e
            ], 401);
        }
    }
}
