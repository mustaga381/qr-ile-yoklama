<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RolKontrolMiddleware
{
    public function handle(Request $request, Closure $next, ...$roller)
    {
        $kullanici = $request->user();

        if (!$kullanici) {
            return redirect()->route('giris.form');
        }

        if (!in_array($kullanici->rol, $roller, true)) {
            abort(403, 'Bu alana erişim yetkiniz yok.');
        }

        return $next($request);
    }
}
