<?php

Route::group(['prefix' => 'admin'], function () {
    Route::resource('seo', 'Larrock\ComponentAdminSeo\AdminSeoController');
});

Breadcrumbs::register('admin.'.LarrockAdminSeo::getName().'.index', function ($breadcrumbs) {
    $breadcrumbs->push(LarrockAdminSeo::getTitle(), '/admin/'.LarrockAdminSeo::getName());
});
