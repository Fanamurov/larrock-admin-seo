<?php

namespace Larrock\ComponentAdminSeo;

use LarrockAdminSeo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Larrock\Core\Traits\AdminMethods;

class AdminSeoController extends Controller
{
    use AdminMethods;

    public function __construct()
    {
        $this->shareMethods();
        $this->middleware(LarrockAdminSeo::combineAdminMiddlewares());
        $this->config = LarrockAdminSeo::shareConfig();
        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['data'] = LarrockAdminSeo::getModel()->orderBy('seo_type_connect')->paginate(30);

        return view('larrock::admin.admin-builder.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $test = Request::create('/admin/'.LarrockAdminSeo::getName(), 'POST', [
            'seo_title' => 'Новый материал',
            'seo_type_connect' => 'default',
        ]);

        return $this->store($test);
    }
}
