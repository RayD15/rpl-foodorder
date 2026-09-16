<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureStorageLink
{
    public function handle(Request $request, Closure $next)
    {
        $link = public_path('storage');
        $target = storage_path('app/public');
        if (is_link($link)) {
            if (realpath($link) === false) {
                @unlink($link);
                @symlink($target, $link);
            }
        } elseif (!file_exists($link)) {
            @symlink($target, $link);
        }
        return $next($request);
    }
}
