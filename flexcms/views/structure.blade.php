@foreach($structure as $row)
    <div class="row {{$row->class}}">
        @foreach($row->columns as $column)
            <div class="col col-sm-{{$column->span->small}} col-md-{{$column->span->medium}} col-lg-{{$column->span->large}} offset-sm-{{$column->offset->small}} offset-md-{{$column->offset->medium}} offset-lg-{{$column->offset->large}} {{$column->class}}">
                @foreach($column->widgets as $widget)
                    {{render_widget($widget)}}
                @endforeach
            </div>
        @endforeach
    </div>
@endforeach
