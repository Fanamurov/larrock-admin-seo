<?php

namespace Larrock\ComponentAdminSeo;

use LarrockAdminSeo;
use Larrock\Core\Component;
use Larrock\Core\Models\Seo;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\Core\Helpers\FormBuilder\FormSelectKey;

class SeoComponent extends Component
{
    public function __construct()
    {
        $this->name = $this->table = 'seo';
        $this->title = 'SEO';
        $this->description = 'Кастомные seo-настройки материалов';
        $this->model = \config('larrock.models.seo', Seo::class);
        $this->addRows();
    }

    protected function addRows()
    {
        $row = new FormInput('seo_title', 'Title');
        $this->setRow($row->setValid('max:255|required')->setTypo()->setInTableAdmin()
            ->setFillable()->setMobileAdminVisible());

        $row = new FormTextarea('seo_description', 'Description');
        $this->setRow($row->setTypo()->setInTableAdmin()->setNotEditor()->setFillable());

        $row = new FormTextarea('seo_keywords', 'Keywords');
        $this->setRow($row->setNotEditor()->setFillable());

        $row = new FormInput('seo_id_connect', 'ID материала (опционально)');
        $this->setRow($row->setInTableAdmin()->setCssClassGroup('uk-width-1-3')->setFillable());

        $row = new FormInput('seo_url_connect', 'URL материала (опционально)');
        $this->setRow($row->setInTableAdmin()->setCssClassGroup('uk-width-1-3')->setFillable());

        $row = new FormSelectKey('seo_type_connect', 'Тип seo');
        $this->setRow($row->setOptions([
            'postfix_global' => 'Постфикс для всего сайта',
            'prefix_global' => 'Префикс для всего сайта',
            'catalog_category_postfix' => 'Постфикс для раздела каталога',
            'catalog_category_prefix' => 'Префикс для раздела каталога',
            'catalog_item_postfix' => 'Постфикс для страницы товара каталога',
            'catalog_item_prefix' => 'Префикс для страницы товара каталога',
            'catalog' => 'Материал каталога',
            'page' => 'Материал статичной страницы',
            'feed' => 'Материал ленты',
            'category' => 'Материал раздела',
            'url' => 'URL',
        ])->setCssClassGroup('uk-width-1-3')->setInTableAdmin()->setFillable());

        return $this;
    }

    public function renderAdminMenu()
    {
        $count = \Cache::rememberForever('count-data-admin-'.LarrockAdminSeo::getName(), function () {
            return LarrockAdminSeo::getModel()->count(['id']);
        });

        return view('larrock::admin.sectionmenu.types.default', ['count' => $count, 'app' => LarrockAdminSeo::getConfig(),
            'url' => '/admin/'.LarrockAdminSeo::getName(), ]);
    }
}
