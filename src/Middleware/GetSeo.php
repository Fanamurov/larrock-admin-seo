<?php

namespace Larrock\ComponentAdminSeo\Middleware;

use View;
use Cache;
use Closure;
use LarrockAdminSeo;

class GetSeo
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
        $get_seo = Cache::rememberForever('SEO_midd', function () {
            $seo = [];

            foreach (LarrockAdminSeo::getRows()['seo_type_connect']->options as $type_key => $type) {
                if (! empty($type_key) && ! array_key_exists($type_key, $seo)) {
                    $seo[$type_key] = null;
                }
            }

            $data = LarrockAdminSeo::getModel()->all();
            foreach ($data as $value) {
                if (! empty($value->seo_type_connect)) {
                    $seo[$value->seo_type_connect] = $value->seo_title;
                    if (strpos($value->seo_type_connect, 'postfix')) {
                        $seo[$value->seo_type_connect] = ' '.$seo[$value->seo_type_connect];
                    }
                    if (strpos($value->seo_type_connect, 'prefix')) {
                        $seo[$value->seo_type_connect] .= ' ';
                    }
                }
            }

            return $seo;
        });

        //Собираем данные закрепленные за URL'ами
        $current_url = last(\Route::current()->parameters());
        $get_seo['url'] = Cache::rememberForever('getSeoUrl'.$current_url, function () use ($current_url) {
            if ($get_data = LarrockAdminSeo::getModel()->whereSeoUrlConnect($current_url)->first()) {
                return $get_data->seo_title;
            }
        });

        View::share('seo_midd', $get_seo);

        return $next($request);
    }
}
