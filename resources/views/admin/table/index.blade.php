<div class="box">
    <div class="box-header with-border">
        <div class="pull-right">
        </div>
        <span>
            <a class="btn btn-sm btn-primary grid-refresh" title="刷新" onclick="window.location.href=window.location.href"><i class="fa fa-refresh"></i><span
                        class="hidden-xs"> 刷新</span></a>
        </span>
    </div>

    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
            <tr>
                @foreach($titles as $title)
                    <th>{{$title}}</th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($rows as $row)
                <tr>
                    @foreach($row as $title=>$value)
                        <td>
                            {{--{{$value}}--}}
                            {!! $value !!}
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>