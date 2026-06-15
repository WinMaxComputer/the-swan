<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1); // ambil prefix dari URL
        if (in_array($locale, ['id', 'en'])) {
            App::setLocale($locale);
            session(['locale' => $locale]); // simpan di session
        } else {
            App::setLocale(session('locale', 'en')); // default
        }

        return $next($request);
    }
}
?>
