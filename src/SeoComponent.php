<?php

namespace Larrock\ComponentAdminSeo;

use Larrock\Core\Component;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\Core\Models\Seo;

class SeoComponent extends Component
{
    public function __construct()
    {
        $this->name = $this->table = 'seo';
        $this->title = 'SEO';
        $this->description = 'Кастомные seo-настройки материалов';
        $this->model = Seo::class;
        $this->addRows();
    }

    protected function addRows()
    {
        $row = new FormInput('seo_title', 'Title');
        $this->rows['seo_title'] = $row->setValid('max:255|required')->setTypo()->setInTableAdmin();

        $row = new FormTextarea('seo_description', 'Description');
        $this->rows['seo_description'] = $row->setTypo()->setInTableAdmin();

        $row = new FormTextarea('seo_keywords', 'Keywords');
        $this->rows['seo_keywords'] = $row;

        $row = new FormInput('id_connect', 'id_connect');
        $this->rows['id_connect'] = $row->setInTableAdmin()->setCssClassGroup('uk-width-1-3');

        $row = new FormInput('url_connect', 'url_connect');
        $this->rows['url_connect'] = $row->setInTableAdmin()->setCssClassGroup('uk-width-1-3');

        $row = new FormInput('type_connect', 'url_connect');
        $this->rows['type_connect'] = $row->setInTableAdmin()->setCssClassGroup('uk-width-1-3');

        return $this;
    }

    public function renderAdminMenu()
    {
        $count = \Cache::remember('count-data-admin-'. $this->name, 1440, function(){
            return Seo::count(['id']);
        });
        return view('larrock::admin.sectionmenu.types.default', ['count' => $count, 'app' => $this, 'url' => '/admin/'. $this->name]);
    }
}