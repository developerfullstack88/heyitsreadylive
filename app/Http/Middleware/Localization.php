<?php

namespace App\Http\Middleware;

use Closure;

class Localization
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
        if(\Session::has('locale'))
        {
            \App::setlocale(\Session::get('locale'));
        }else{
          $ipInfo=ipInfo();
          switch($ipInfo->countryCode){
            case 'IN':
              \App::setlocale('en');
              \Session::put('locale', 'en');
            break;
            case 'CA':
              \App::setlocale('ca');
              \Session::put('locale', 'ca');
            break;
            case 'ES':
              \App::setlocale('es');
              \Session::put('locale', 'es');
            break;
            case 'AU':
              \App::setlocale('aus');
              \Session::put('locale', 'aus');
            break;
            case 'NZ':
              \App::setlocale('nz');
              \Session::put('locale', 'nz');
            break;
            default:
              \App::setlocale('en');
              \Session::put('locale', 'en');
          }
        }
        return $next($request);
      }

}
