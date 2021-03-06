<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;

class authJWT
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
            
                try {

                   $user = JWTAuth::toUser($request->input('token'));

                     } catch (Exception $e) {

                   if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                       return response()->json(['error'=>'Token is Invalid']);

                   }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                       return response()->json(['error'=>'Token is Expired']);

                   }else{

                       return response()->json(['error'=>'Something is wrong']);

                   }

            }

            
            
		return $next($request);
	}
                  
           public function render($request, Exception $e){
         

                         if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                          {
                             return response(['Token is invalid'], 401);
                          }
                          if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                          {
                             return response(['Token has expired'], 401);
                          }

                          return parent::render($request, $e);
                        }
}
