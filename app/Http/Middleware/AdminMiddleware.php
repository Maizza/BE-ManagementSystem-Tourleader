<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek apakah user yang sedang login adalah admin
        if (auth()->user() && auth()->user()->role !== 'admin') {
            return redirect('/'); // Redirect jika bukan admin
        }

        return $next($request); // Lanjutkan jika admin
    }
}

