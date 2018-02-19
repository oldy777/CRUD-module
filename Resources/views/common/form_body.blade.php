<div class="tabbable-line">
    <ul class="nav nav-tabs ">
        <li class="active">
            <a href="#tab_1" data-toggle="tab">Общее</a>
        </li>
        @foreach($form->getFields() as $name=>$value)
            @if( $value instanceof \Kris\LaravelFormBuilder\Fields\ChildFormType
                && $value->getOption('tab')
            )
                <li>
                    <a href="#tab_{{ $name }}" data-toggle="tab">{{ $value->getOption('label') }}</a>
                </li>
            @endif
        @endforeach
    </ul>

    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            @foreach($form->getFields() as $name=>$value)
                @if(!$value instanceof \Kris\LaravelFormBuilder\Fields\ChildFormType
                    || !$value->getOption('tab')
                )
                    {!! form_row($value) !!}
                @endif
            @endforeach
        </div>

        @foreach($form->getFields() as $name=>$value)
            @if( $value instanceof \Kris\LaravelFormBuilder\Fields\ChildFormType
                && $value->getOption('tab')
            )
                <div class="tab-pane" id="tab_{{ $name }}">
                    {!! form_row($value) !!}
                </div>
            @endif
        @endforeach
    </div>
</div>