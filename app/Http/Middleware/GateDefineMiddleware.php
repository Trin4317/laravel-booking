<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class GateDefineMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // get all permissions associate with user's role
            $permissions = Permission::whereHas('roles', function($query) {
                $query->where('id', auth()->user()->role_id);
            })->get();

            // define Gate for those permissions
            // undefined permissions will automatically return false
            foreach ($permissions as $permission) {
                Gate::define($permission->name, fn() => true);
            }
        }

        return $next($request);
    }
}
