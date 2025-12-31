<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBusinessIdMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!$user->roles()->whereIn('name', $roles)->exists()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $businessIdParam = (int)$request->input('business_id');
        $businessIdAuthUser = $user->business_id;
        $roleName = $user->roles->first()?->name;
        // dd($businessIdParam, $businessIdAuthUser);
        if($roleName == "business_admin"){
            if($businessIdParam !== null && $businessIdParam !== 0 && $businessIdParam !== $businessIdAuthUser ){
                return response()->json(['message' => 'Forbidden'], 403);
            }elseif($businessIdParam == null){
                return $next($request);
            }
        }elseif ($roleName == "superadmin") {
            if(!$businessIdParam){
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }

        if($roleName == "superadmin" || $roleName == "business_admin"){
            return $next($request);
        }

    }
}
