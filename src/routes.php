<?php

use Larrock\ComponentAdminSeo\AdminSeoController;

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu']], function(){
    Route::resource('seo', AdminSeoController::class);
});