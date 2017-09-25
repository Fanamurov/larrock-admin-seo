<?php

namespace Larrock\ComponentAdminSeo;

use Alert;
use Breadcrumbs;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Larrock\Core\AdminController;
use Larrock\Core\Component;
use Larrock\ComponentAdminSeo\Facades\LarrockSeo;
use Redirect;
use Validator;
use View;
use JsValidator;

class AdminSeoController extends AdminController
{
	public function __construct()
	{
        $this->config = LarrockSeo::shareConfig();

        \Config::set('breadcrumbs.view', 'larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. LarrockSeo::getName() .'.index', function($breadcrumbs){
            $breadcrumbs->push(LarrockSeo::getTitle(), '/admin/'. LarrockSeo::getName());
        });
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
	public function create(Request $request)
	{
        $test = Request::create('/admin/'. LarrockSeo::getName(), 'POST', [
            'seo_title' => 'Новый материал'
        ]);
        return $this->store($test);
	}
}