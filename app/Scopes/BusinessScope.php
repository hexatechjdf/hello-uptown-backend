<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Request;

class BusinessScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();
        $businessIdParam = (int)request()->input('business_id');

        $roleName = $user->roles->first()?->name;
        $businessIdAuthUser = $user->business_id;
         if($roleName == "business_admin"){
            if($businessIdParam !== $businessIdAuthUser){
                return response()->json(['message' => 'Forbidden'], 403);
            }
        }
        $businessId = (int)request()->query('business_id');
        if ($businessId) {
            $builder->where($model->getTable() . '.business_id', $businessId);
        }
    }
}
