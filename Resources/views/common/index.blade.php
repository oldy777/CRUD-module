@extends('core::layouts.app')

@section('page-title', $title)

@section('content')
    <div class="portlet light">
        <div class="portlet-title">

            <div class="actions btn-set">
                <a href="{{ action($controller.'@create') }}" class="btn btn-default"> Добавить</a>
            </div>

            <div class="table-group-actions">
                <select class="bs-select form-control input-medium " >
                    <option value=""><?=_('С отмеченными')?>...</option>
                    <option value="delete"><?=_('Удалить')?></option>
                </select>
                <button class="btn yellow table-group-action-submit" id="deleteListItems" data-mod="#module"><i class="fa fa-check"></i> Выполнить</button>
            </div>
        </div>

        <div class="portlet-body">
            <div class="table-container">
                <div class="table-scrollable">
                    <table class="table table-striped table-bordered table-hover" >
                        <thead>
                        <tr role="row" class="heading">
                            <th width="1%">
                                <input type="checkbox" class="group-checkable" />
                            </th>
                            <th width="1%">
                                ID
                            </th>

                            @if($sortable)
                            <th width="3%"><?=_('Поз')?>.</th>
                            @endif

                            @foreach($fields as $name=>$field)
                                <th>{{ $field['title'] }}</th>
                            @endforeach

                            <th width="1%">
                                <?=_('Действия')?>
                            </th>
                        </tr>
                        </thead>
                        <tbody>

                            @foreach($items as $item)
                                <tr align="center" class="odd">
                                    <td><input type="checkbox" value="{{ $item->id }}" name="id[]"></td>
                                    <td style="font-size:11px;color:#999;">{{ $item->id }}</td>

                                    @if($sortable)
                                    <td class="item" id="{{ $item->pos }}" style="font-size:11px;color:#999;">
                                        <input class="inp" id="{{ $item->id }}" style="width:40px; display:none; font-size:11px; text-align:center;" type="text" value="{{ $item->pos }}" rel="{{ $item->getTable() }}" cat="" cat_val="" />
                                        <span style="display:block; width:40px;">{{ $item->pos }}</span></td>
                                    @endif

                                    @foreach($fields as $name=>$field)
                                        <td align="left">
                                            @isset($field['route'])
                                                <a href="{{ route($field['route'], $item->id) }}">
                                            @endisset
                                                {{ $item->$name }}
                                            @isset($field['route'])
                                                </a>
                                            @endisset
                                        </td>
                                    @endforeach


                                    <td nowrap="nowrap">
                                        <a class="btn btn-icon-only blue" href="{{ action($controller.'@edit', $item->id) }}" title="<?=_('Редактировать')?>"><i class="fa fa-pencil"></i></a>
                                        <form id="del-form-{{ $item->id }}" action="{{ action($controller.'@destroy', $item->id) }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                        </form>
                                        <a href="javascript:del('del-form-{{ $item->id }}')" title="<?=_('Удалить')?>" class="btn btn-icon-only red"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                {{ $items->links('crud::pagination.default') }}
            </div>
        </div>


    </div>

@endsection

@push('scripts')
    @if($sortable)
        <script src="/admin/js/sort.js" type="text/javascript"></script>
    @endif
@endpush