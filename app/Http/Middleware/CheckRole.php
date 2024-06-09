<?php

namespace App\Http\Middleware;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Closure;


class CheckRole{
    public function adminRole(Request $request, Closure $next){
        Auth::check();
        if(Auth::user()->role === 'admin'){
            return $next($request);
        }
        return redirect()->back()->with('error', "Nimate dovoljenja za tole zahtevo!");
    }
}