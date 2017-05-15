<?php namespace Edutalk\Base\CustomFields\Http\DataTables;

use Edutalk\Base\CustomFields\Models\FieldGroup;
use Edutalk\Base\Http\DataTables\AbstractDataTables;
use Yajra\Datatables\Engines\CollectionEngine;
use Yajra\Datatables\Engines\EloquentEngine;
use Yajra\Datatables\Engines\QueryBuilderEngine;

class FieldGroupsListDataTable extends AbstractDataTables
{
    protected $model;

    public function __construct()
    {
        $this->model = FieldGroup::select(['id', 'title', 'status', 'order', 'created_at', 'updated_at']);
    }

    public function headings()
    {
        return [
            'title' => [
                'title' => trans('edutalk-core::datatables.heading.title'),
                'width' => '25%',
            ],
            'status' => [
                'title' => trans('edutalk-core::datatables.heading.status'),
                'width' => '10%',
            ],
            'order' => [
                'title' => trans('edutalk-core::datatables.heading.order'),
                'width' => '10%',
            ],
            'created_at' => [
                'title' => trans('edutalk-core::datatables.heading.created_at'),
                'width' => '10%',
            ],
            'updated_at' => [
                'title' => trans('edutalk-core::datatables.heading.updated_at'),
                'width' => '10%',
            ],
            'actions' => [
                'title' => trans('edutalk-core::datatables.heading.actions'),
                'width' => '20%',
            ],
        ];
    }

    public function columns()
    {
        return [
            ['data' => 'id', 'name' => 'id', 'searchable' => false, 'orderable' => false],
            ['data' => 'title', 'name' => 'title'],
            ['data' => 'status', 'name' => 'status'],
            ['data' => 'order', 'name' => 'order', 'searchable' => false],
            ['data' => 'created_at', 'name' => 'created_at', 'searchable' => false],
            ['data' => 'updated_at', 'name' => 'updated_at', 'searchable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ];
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('admin::custom-fields.index.post'), 'POST');

        $this
            ->addFilter(1, form()->text('title', '', [
                'class' => 'form-control form-filter input-sm',
                'placeholder' => trans('edutalk-core::datatables.search') . '...',
            ]))
            ->addFilter(2, form()->select('status', [
                '' => trans('edutalk-core::datatables.select') . '...',
                'activated' => trans('edutalk-core::base.status.activated'),
                'disabled' => trans('edutalk-core::base.status.disabled'),
            ], null, ['class' => 'form-control form-filter input-sm']));

        $this->withGroupActions([
            '' => trans('edutalk-core::datatables.select') . '...',
            'deleted' => trans('edutalk-core::datatables.delete_these_items'),
            'activated' => trans('edutalk-core::datatables.active_these_items'),
            'disabled' => trans('edutalk-core::datatables.disable_these_items'),
        ]);

        return $this->view();
    }

    /**
     * @return CollectionEngine|EloquentEngine|QueryBuilderEngine|mixed
     */
    protected function fetchDataForAjax()
    {
        return datatable()->of($this->model)
            ->rawColumns(['actions'])
            ->editColumn('id', function ($item) {
                return form()->customCheckbox([['id[]', $item->id]]);
            })
            ->editColumn('status', function ($item) {
                return html()->label(trans('edutalk-core::base.status.' . $item->status), $item->status);
            })
            ->addColumn('actions', function ($item) {
                /*Edit link*/
                $activeLink = route('admin::custom-fields.field-group.update-status.post', ['id' => $item->id, 'status' => 'activated']);
                $disableLink = route('admin::custom-fields.field-group.update-status.post', ['id' => $item->id, 'status' => 'disabled']);
                $deleteLink = route('admin::custom-fields.field-group.delete.delete', ['id' => $item->id]);

                /*Buttons*/
                $editBtn = link_to(route('admin::custom-fields.field-group.edit.get', ['id' => $item->id]), trans('edutalk-core::datatables.edit'), ['class' => 'btn btn-sm btn-outline green']);
                $activeBtn = ($item->status != 'activated') ? form()->button(trans('edutalk-core::datatables.active'), [
                    'title' => trans('edutalk-core::datatables.active_this_item'),
                    'data-ajax' => $activeLink,
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline blue btn-sm ajax-link',
                    'type' => 'button',
                ]) : '';
                $disableBtn = ($item->status != 'disabled') ? form()->button(trans('edutalk-core::datatables.disable'), [
                    'title' => trans('edutalk-core::datatables.disable_this_item'),
                    'data-ajax' => $disableLink,
                    'data-method' => 'POST',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline yellow-lemon btn-sm ajax-link',
                    'type' => 'button',
                ]) : '';
                $deleteBtn = form()->button(trans('edutalk-core::datatables.delete'), [
                    'title' => trans('edutalk-core::datatables.delete_this_item'),
                    'data-ajax' => $deleteLink,
                    'data-method' => 'DELETE',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                    'type' => 'button',
                ]);

                return $editBtn . $activeBtn . $disableBtn . $deleteBtn;
            });
    }
}
