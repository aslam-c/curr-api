<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;

use App\User;

class decodejwt
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwt = $request->header('Authorization');
        if ($jwt) {
            try {
                $secret = '1234567890ABCD';
                $decoded = JWT::decode($jwt, $secret, array('HS256'));
                $user_id = $decoded->user_id;
                $u = User::find($user_id);
                if (!$u) {
                    $data = [
                        'status' => 403,
                        'response' => 'Unauthorized'
                    ];
                    return response()->json($data, $data['status']);
                }
            } catch (SignatureInvalidException $se) {
                $data = [
                    'status' => 403,
                    'response' => 'Invalid signature jwt'
                ];
                return response()->json($data, $data['status']);
            } catch (ExpiredException $exe) {
                $data = [
                    'status' => 403,
                    'response' => 'expired jwt'
                ];
                return response()->json($data, $data['status']);
            }
        } else {
            $data = [
                'status' => 403,
                'response' => 'jwt is missing'
            ];

            return response()->json($data, $data['status']);
        }

        return $next($request);
    }
}
