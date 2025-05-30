<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class BlockAdminInProduction
{
    public function handle($request, Closure $next)
    {
        if (App::environment('production') && $request->is('admin*')) {
            return redirect('/'); // Chuyển về trang chủ
        }

        return $next($request);
    }
}
