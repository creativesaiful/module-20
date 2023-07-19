<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Usre registration

    public function userRegistration(Request $request){
        User::create([
           'firstName'=>$request->input('firstName'),
        ]);
    }
}
