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

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
	public function store(Request $request)
	{
        $validator = Validator::make($request->all(), Component::_valid_construct(LarrockSeo::getValid()));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

		if(LarrockSeo::getModel()->fill($request->all())->save()){
			Alert::add('successAdmin', 'Seo '. $request->input('seo_title') .' добавлен')->flash();
			return Redirect::to('/admin/'. LarrockSeo::getName() .'/'. $data->id .'/edit')->withInput();
		}

		Alert::add('errorAdmin', 'Seo '. $request->input('seo_title') .' не добавлен')->flash();
		return back()->withInput();
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$data['data'] = LarrockSeo::getModel()->findOrFail($id);
        $data['app'] = LarrockSeo::tabbable($data['data']);

        $validator = JsValidator::make(Component::_valid_construct(LarrockSeo::getConfig(), 'update', $id));
        View::share('validator', $validator);

        Breadcrumbs::register('admin.'. LarrockSeo::getName() .'.edit', function($breadcrumbs, $data)
        {
            $breadcrumbs->parent('admin.'. LarrockSeo::getName() .'.index');
            $breadcrumbs->push($data->seo_title);
        });

        return view('larrock::admin.admin-builder.edit', $data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
	public function update(Request $request, $id)
	{
        $validator = Validator::make($request->all(), Component::_valid_construct(LarrockSeo::getConfig(), 'update', $id));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

		if(LarrockSeo::getModel()->find($id)->fill($request->all())->save()){
			Alert::add('successAdmin', 'Seo '. $request->input('seo_title') .' изменен')->flash();
            \Cache::flush();
			return back();
		}

		Alert::add('errorAdmin', 'Seo '. $request->input('seo_title') .' не изменен')->flash();
		return back()->withInput();
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
	public function destroy(Request $request, $id)
	{
		if($data = LarrockSeo::getModel()->find($id)){
            if($data->delete()){
                Alert::add('successAdmin', 'Материал успешно удален')->flash();
            }else{
                Alert::add('errorAdmin', 'Материал не удален')->flash();
            }
        }else{
            Alert::add('errorAdmin', 'Такого материала больше нет')->flash();
        }

        if($request->get('place') === 'material'){
            return Redirect::to('/admin/'. LarrockSeo::getName());
        }
        return back();
	}
}