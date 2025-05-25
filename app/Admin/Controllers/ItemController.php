<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\ApproveItems;
use App\Admin\Actions\Grid\UnapproveItems;
use App\Models\Item;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use App\Mail\PostApprovedMail;
use Illuminate\Support\Facades\Mail;
class ItemController extends AdminController
{
    protected $title = 'Items';

    protected function grid()
    {
        $grid = new Grid(new Item());

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', 'User')->sortable();
        $grid->column('images', 'Image')->display(function ($images) {
            if (is_array($images) && count($images) > 0 && !empty($images[0]['image_url'])) {
                return '<img src="' . $images[0]['image_url'] . '" width="60"/>';
            }
            return '';
        })->sortable(false);
        $grid->column('title', 'Title')->sortable();
        $grid->column('category.name', 'Category')->sortable();
        $grid->column('location', 'Location')->sortable();
        $grid->column('quantity', 'Quantity')->sortable();
        $grid->column('status', 'Status')->sortable();
        $grid->column('is_approved', 'Approved')->bool()->sortable();
        $grid->column('created_at', 'Created At')->sortable();
        $grid->column('updated_at', 'Updated At')->sortable();

        $grid->filter(function ($filter) {
            $filter->equal('is_approved', 'Approval Status')->radio([
                '' => 'All',
                0 => 'Not Approved',
                1 => 'Approved',
            ]);
            $filter->like('title', 'Title');
        });

        // Batch action: Approve multiple items
        $grid->batchActions(function ($batch) {
            $batch->add(new ApproveItems());
            $batch->add(new UnapproveItems()); // thêm dòng này

        });

        return $grid;
    }


    protected function detail($id)
    {
        $show = new Show(Item::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user_id', 'User ID');
        $show->field('title', 'Title');
        $show->field('description', 'Description');
        $show->field('category_id', 'Category');
        $show->field('location', 'Location');
        $show->field('quantity', 'Quantity');
        $show->field('status', 'Status');
        $show->field('is_approved', 'Approved');

        // Hiển thị các ảnh liên quan
        $show->images('Images', function ($images) {
            $images->resource('/admin/images'); // Đường dẫn quản lý ảnh nếu có
            $images->image_url('Image')->image('', 100, 100); // Hiển thị ảnh thumbnail
            $images->created_at();
        });

        return $show;
    }



    // protected function form()
// {
//     $form = new Form(new Item());

    //     $form->display('id', 'ID');
//     $form->number('user_id', 'User ID');
//     $form->text('title', 'Title');
//     $form->textarea('description', 'Description');
//     $form->number('category_id', 'Category');
//     $form->text('location', 'Location');
//     $form->number('quantity', 'Quantity')->default(1);
//     $form->datetime('expired_at', 'Expiration Date')->default(date('Y-m-d H:i:s'));
//     $form->text('status', 'Status')->default('Available');
//     $form->switch('is_approved', 'Approve Item')->states([
//         'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
//         'off' => ['value' => 0, 'text' => 'No', 'color' => 'danger'],
//     ]);

    //     // Hook gửi mail khi item được duyệt
//     $form->saved(function (Form $form) {
//         $item = $form->model();

    //         // Nếu item vừa được duyệt mà trước đó chưa duyệt
//         if ($item->is_approved && $item->getOriginal('is_approved') == false) {
//             Mail::to($item->user->email)->send(new PostApprovedMail($item));
//         }
//     });

    //     return $form;
// }

}
