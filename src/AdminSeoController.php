<?php

namespace Larrock\ComponentAdminSeo;

use Illuminate\Http\Request;

use Larrock\ComponentAdminSeo\Facades\LarrockSeo;
use Larrock\Core\Traits\AdminMethods;
use Illuminate\Routing\Controller;

class AdminSeoController extends Controller
{
    use AdminMethods;

    public function __construct()
    {
        $this->shareMethods();
        $this->middleware(LarrockSeo::combineAdminMiddlewares());
        $this->config = LarrockSeo::shareConfig();
        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['data'] = LarrockSeo::getModel()->orderBy('seo_type_connect')->paginate(30);
        return view('larrock::admin.admin-builder.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $test = Request::create('/admin/'. LarrockSeo::getName(), 'POST', [
            'seo_title' => 'Новый материал'
        ]);
        return $this->store($test);
    }
}