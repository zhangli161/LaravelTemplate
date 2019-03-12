<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid;

Encore\Admin\Form::forget(['map', 'editor']);
Admin::disablePjax();
Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    $navbar->left(view("admin.totop"));
//    $navbar->left("<script src=\"https://cdn.staticfile.org/vue/2.2.2/vue.min.js\"></script>");
});

Grid::init(function (Grid $grid) {

//    $grid->disableRowSelector();
});
