<?php

namespace App\Admin\Controllers;

use App\Message;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Message';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Message());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('lawyer_id', __('Lawyer id'));
        $grid->column('consultation_id', __('Consultation id'));
        $grid->column('type_status', __('Type status'));
        $grid->column('type_message', __('Type message'));
        $grid->column('status_message', __('Status message'));
        $grid->column('message', __('Message'));
        $grid->column('date', __('Date'));
        $grid->column('time', __('Time'));
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
        $show = new Show(Message::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('lawyer_id', __('Lawyer id'));
        $show->field('consultation_id', __('Consultation id'));
        $show->field('type_status', __('Type status'));
        $show->field('type_message', __('Type message'));
        $show->field('status_message', __('Status message'));
        $show->field('message', __('Message'));
        $show->field('date', __('Date'));
        $show->field('time', __('Time'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Message());

        $form->number('user_id', __('User id'));
        $form->number('lawyer_id', __('Lawyer id'));
        $form->number('consultation_id', __('Consultation id'));
        $form->number('type_status', __('Type status'));
        $form->number('type_message', __('Type message'));
        $form->number('status_message', __('Status message'));
        $form->text('message', __('Message'));
        $form->text('date', __('Date'));
        $form->text('time', __('Time'));

        return $form;
    }
}
