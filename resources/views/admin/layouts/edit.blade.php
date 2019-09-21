@extends('admin.layouts.app')

@section('header')
    @include('admin.include.header')
@endsection('header')

@section('content')
    <section class="content-header">
        <h1>
            {{ $title[0] }}編集
        </h1>
        <ol class="breadcrumb">
            <!-- li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li-->
        </ol>
    </section>

    <div class="container_fluid">
        <section class="content">
            <div class="box">

                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6"></div>
                    </div>
                    <form class="form-horizontal" role="form" method="POST" action="{{ action('Admin\\'.$title[1].'Controller@update', ['id' => $data->id]) }}" enctype="multipart/form-data">
                        @csrf

                        @method('PUT')

                        @foreach($forms as $key => $form)
                            @switch($form['type'])
                                @case('hide')
                                @break
                                @case('textarea')
                                @include('admin.parts.form_textarea')
                                @break
                                @case('html')
                                @include('admin.parts.form_html')
                                @break
                                @case('select')
                                @include('admin.parts.form_select')
                                @break
                                @case('datetime')
                                @include('admin.parts.form_datetime')
                                @break
                                @case('file')
                                @include('admin.parts.form_file')
                                @break
                                @case('json')
                                @include('admin.parts.form_json')
                                @break
                                @case('password')
                                @include('admin.parts.form_password')
                                @break
                                @default
                                @include('admin.parts.form_text')
                            @endswitch
                        @endforeach

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    登録
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </section>
    </div>
@endsection('content')

@section('footer')
    @include('admin.include.footer')
@endsection('footer')
