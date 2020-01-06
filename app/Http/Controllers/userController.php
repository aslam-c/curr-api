<?php

namespace App\Http\Controllers;


use App\Events\jwtstring;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;

use Firebase\JWT\JWT;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class userController extends Controller
{
    public function login(Request $r)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];
        $v = Validator::make($r->all(), $rules);
        if ($v->fails()) {
            $data = [
                'status' => 422,
                'errors' => $v->errors()
            ];
        } else {
            $u = User::where(['email' => $r->email])->first();
            if (!is_null($u)) {
                if (Hash::check($r->password, $u->password)) {
                    $jwt = event(new jwtstring(['key' => 'user_id', 'value' => $u->id]))[0];
                    $data = [
                        'status' => 200,
                        'response' => 'login success',
                        'jwt' => $jwt
                    ];
                } else {
                    $data = [
                        'status' => 403,
                        'response' => 'Invalid password'
                    ];
                }
            } else {
                $data = [
                    'status' => 403,
                    'response' => 'Invalid credentials'
                ];
            }
        }
        // $jwt = event(new jwtstring(''))[0];
        return response()->json($data, $data['status']);
    }

    public function register(Request $r)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ];
        $msgs = ['email.email' => 'We need a real e-mail address'];
        $validator = Validator::make($r->all(), $rules, $msgs);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $data = [
                'status' => 422,
                'errors' => $validator->errors()
            ];
        } else {

            $user = User::create([
                'name' => $r->name,
                'email' => $r->email,
                'password' => Hash::make($r->password)
            ]);


            $data = [
                'status' => 201,
                'response' => 'Created',
                'data' => $user
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function convert(Request $r)
    {
        $uid = $r->user_id;
        $from = $r->from;

        try {
            $guzzle = new Client();
            $api_key = '6a14acdb221dfbd55fe5';
            $req = $guzzle->request("GET", "https://free.currconv.com/api/v7/convert?q=" . $from . "_INR&compact=ultra&apiKey=" . $api_key);
            $api_data = json_decode($req->getBody()->getContents());
            $data = [
                'status' => 200,
                'rate' => $api_data
            ];
        } catch (RequestException $ex) {
            $data = [
                'status' => 404,
                'response' => 'failed to fetch currency rate'
            ];
        }

        return response()->json($data, $data['status']);
    }

    public function getjwt(Request $r)
    {
        $key = $r->key;
        $value = $r->value;
        $data = ['key' => $key, 'value' => $value];
        $jwt = event(new jwtstring($data))[0];
        return response()->json($jwt, 200);
    }

    //     public function decode(Request $r)
    //     {
    //         $jwt = $r->jwt;
    //         $secret = config('services.jwt.secret');

    //         $decoded = JWT::decode($jwt, $secret, array('HS256'));


    //         return response()->json($decoded, 200);
    //     }
}
