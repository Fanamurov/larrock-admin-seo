<?php

namespace Larrock\ComponentAdminSeo\Middleware;

use Cache;
use Closure;
use View;
use Larrock\ComponentAdminSeo\Facades\LarrockSeo;

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
        $get_seo = Cache::remember('SEO_midd', 1440, function() {
            $seo = [];

            foreach (LarrockSeo::getRows()['seo_type_connect']->options as $type_key => $type){
                if( !empty($type_key) && !array_key_exists($type_key, $seo)){
                    $seo[$type_key] = NULL;
                }
            }

            $data = LarrockSeo::getModel()->all();
            foreach ($data as $value){
                if( !empty($value->seo_type_connect)){
                    $seo[$value->seo_type_connect] = $value->seo_title;
                    if(strpos($value->seo_type_connect, 'postfix')){
                        $seo[$value->seo_type_connect] = ' '. $seo[$value->seo_type_connect];
                    }
                    if(strpos($value->seo_type_connect, 'prefix')){
                        $seo[$value->seo_type_connect] = $seo[$value->seo_type_connect] .' ';
                    }
                }
            }
            return $seo;
        });

        //Собираем данные закрепленные за URL'ами
        $current_url = last(\Route::current()->parameters());
        $get_seo['url'] = Cache::remember('getSeoUrl'. $current_url, 1440, function() use ($current_url){
            if($get_data = LarrockSeo::getModel()->whereSeoUrlConnect($current_url)->first()){
                return $get_data->seo_title;
            }
        });

        View::share('seo_midd', $get_seo);
        return $next($request);
    }
}
