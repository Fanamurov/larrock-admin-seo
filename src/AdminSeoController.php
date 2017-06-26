<?php

namespace Larrock\ComponentAdminSeo;

use Alert;
use Breadcrumbs;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Larrock\Core\Component;
use Larrock\Core\Models\Seo;
use Redirect;
use Validator;
use View;
use JsValidator;

class AdminSeoController extends Controller
{
	protected $config;

	public function __construct()
	{
        $Component = new SeoComponent();
        $this->config = $Component->shareConfig();

        Breadcrumbs::setView('larrock::admin.breadcrumb.breadcrumb');
        Breadcrumbs::register('admin.'. $this->config->name .'.index', function($breadcrumbs){
            $breadcrumbs->push($this->config->title, '/admin/'. $this->config->name);
        });
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$data['data'] = Seo::orderBy('type_connect')->paginate(30);
		//Для каждого пункта, где сеошка прикреплена по id_connect, достаем url
        /** @noinspection ForeachSourceInspection */
        foreach($data['data'] as $data_key => $data_value){
			if(empty($data_value['url_connect'])){
				$data['data'][$data_key]['url_connect'] = '~~~';
			}
		}
		return view('larrock::admin.admin-builder.index', $data);
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
        $test = Request::create('/admin/seo', 'POST', [
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
        $validator = Validator::make($request->all(), Component::_valid_construct($this->config->valid));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

		$data = new Seo();
		if($data->fill($request->all())->save()){
			Alert::add('successAdmin', 'Seo '. $request->input('seo_title') .' добавлен')->flash();
			return Redirect::to('/admin/'. $this->config->name .'/'. $data->id .'/edit')->withInput();
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
		$data['data'] = Seo::findOrFail($id);
        $data['app'] = $this->config->tabbable($data['data']);

        $validator = JsValidator::make(Component::_valid_construct($this->config, 'update', $id));
        View::share('validator', $validator);

        Breadcrumbs::register('admin.'. $this->config->name .'.edit', function($breadcrumbs, $data)
        {
            $breadcrumbs->parent('admin.'. $this->config->name .'.index');
            $breadcrumbs->push($data->seo_title);
        });

        return view('admin.admin-builder.edit', $data);
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
        $validator = Validator::make($request->all(), Component::_valid_construct($this->config, 'update', $id));
        if($validator->fails()){
            return back()->withInput($request->except('password'))->withErrors($validator);
        }

		$data = Seo::find($id);
		if($data->fill($request->all())->save()){
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
		if($data = Seo::find($id)){
            if($data->delete()){
                Alert::add('successAdmin', 'Материал успешно удален')->flash();
            }else{
                Alert::add('errorAdmin', 'Материал не удален')->flash();
            }
        }else{
            Alert::add('errorAdmin', 'Такого материала больше нет')->flash();
        }

        if($request->get('place') === 'material'){
            return Redirect::to('/admin/'. $this->config->name);
        }
        return back();
	}
}
