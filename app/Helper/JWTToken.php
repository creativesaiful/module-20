<?php

namespace App\Helper;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class JWTToken
{
    public static function createJWToken($userEmail):string{

        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time()+60*60,
            'userEmail' =>$userEmail
        ];

        return JWT::encode($payload, $key, 'HS256');


    }

    public static function verifyJWTToken($jwt){
         $key = env('JWT_KEY');
         $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
         return $decoded->userEmail;
    }
}
