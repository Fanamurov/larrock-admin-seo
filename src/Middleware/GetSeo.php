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
                    if($value->seo_type_connect === 'default'){
                        $seo['url'][$value->seo_url_connect] = $value->seo_title;
                    }else{
                        $seo[$value->seo_type_connect] = $value->seo_title;
                        if(strpos($value->seo_type_connect, 'postfix')){
                            $seo[$value->seo_type_connect] = ' '. $seo[$value->seo_type_connect];
                        }
                        if(strpos($value->seo_type_connect, 'prefix')){
                            $seo[$value->seo_type_connect] = $seo[$value->seo_type_connect] .' ';
                        }
                    }
                }
            }
            return $seo;
        });

        View::share('seo_midd', $get_seo);
        return $next($request);
    }
}