<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('username', __('Username'));
        $grid->column('email', __('Email'));
        $grid->column('phone', __('Phone'));
        $grid->column('address', __('Address'));
        $grid->column('profile_image', __('Profile image'))->display(function ($image) {
            if ($image) {
                return '<img src="' . $image . '" width="60"/>';
            }
            return '';
        })->sortable(false);
        $grid->column('email_verified_at', __('Email verified at'));
        $grid->column('password', __('Password'));
        $grid->column('remember_token', __('Remember token'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('username', __('Username'));
        $show->field('email', __('Email'));
        $show->field('phone', __('Phone'));
        $show->field('address', __('Address'));
        $show->field('profile_image', __('Profile image'))->as(function ($image) {
            if ($image) {
                return '<img src="' . $image . '" width="120"/>';
            }
            return '';
        })->unescape();
        $show->field('email_verified_at', __('Email verified at'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    //     protected function form()
//     {
//         $form = new Form(new User());

    //         $form->text('name', __('Name'));
//         $form->text('username', __('Username'));
//         $form->email('email', __('Email'));
//         $form->mobile('phone', __('Phone'));
//         $form->text('address', __('Address'));
// $form->image('profile_image', __('Profile image'));
//         $form->datetime('email_verified_at', __('Email verified at'))->default(date('Y-m-d H:i:s'));
//         $form->password('password', __('Password'));
//         $form->text('remember_token', __('Remember token'));

    //         return $form;
//     }
}
