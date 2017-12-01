<?php

Route::group(['prefix' => 'admin'], function(){
    Route::resource('seo', 'Larrock\ComponentAdminSeo\AdminSeoController');
});