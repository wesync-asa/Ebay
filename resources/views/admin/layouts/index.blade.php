@extends('admin.layouts.app')

@section('header')
    @include('admin.include.header')
@endsection('header')

@section('content')
    <section class="content-header">
        <h1>
            {{ $title[0] }}管理
        </h1>
        <ol class="breadcrumb">
            <!-- li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li-->
        </ol>
    </section>

    <div class="container_fluid">
        <section class="content">
            <div class="col-sm-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-4"><a class="btn btn-primary" style="float: right;" href="{{ route('admin.'. lcfirst($title[1]) .'.create') }}">追加</a></div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6"></div>
                            <div class="col-sm-6"></div>
                        </div>

                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
                                        <thead>
                                        <tr role="row">
                                            @foreach($tables['fields'] as $key=>$val)
                                                <th class="{{ $val['class'] }}">{{ $val['label'] }}</th>
                                            @endforeach
                                            <th>&nbsp;</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($lists as $key => $list)
                                            <tr role="row" class="odd">
                                                @foreach ($tables['fields'] as $key => $val)
                                                    <td>
                                                        @if (is_array($val['value']))
                                                            @foreach($val['value'] as $k=>$v)
                                                                @if($k) <br/> @endif
                                                                {{ $list->{$v} }}
                                                            @endforeach
                                                        @else
                                                            {{ $list->{$val['value']} }}
                                                        @endif
                                                    </td>
                                                @endforeach
                                                <td>
                                                    @foreach ($tables['actions']['content'] as $key => $val)
                                                        @switch($val['action'])
                                                            @case('edit')
                                                            <a href="{{ action('Admin\\'.$title[1].'Controller@edit', $list->id)}}" class="btn btn-warning col-sm-2 col-xs-3 btn-margin">
                                                                {{ $val['label'] }}
                                                            </a>
                                                            @break
                                                            @case('destroy')
                                                            @component('admin.parts.btn-del')
                                                                @slot('table', strtolower($title[1]))
                                                                @slot('id', $list->id)
                                                            @endcomponent
                                                            @break
                                                            @default
                                                            @break
                                                        @endswitch
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection('content')


@section('footer')
    @include('admin.include.footer')
@endsection('footer')
