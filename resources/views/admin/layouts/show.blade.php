@extends('admin.layouts.app')

@section('header')
    @include('admin.include.header')
@endsection('header')

@section('content')
    <section class="content-header">
        <h1>
            {{ $title[0] }}詳細
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

                        @csrf

                    <form action="" method="post" class="form-horizontal">
                        @foreach($forms as $key => $form)
                        @if($form['type'] == 'hide')
                            @continue;
                        @endif
                        <div class="form-group">

                            <label class="control-label col-md-4"
                                   for="first-name">{{ $form['label'] }}</label>
                            <div class="col-md-8">
                            @switch($form['type'])
                                @case('hide')
                                @break
                                @case('textarea')
                                {{ $data->$key }}
                                @break
                                @case('html')
                                    {{ $data->$key }}
                                @break
                                @case('select')
                                    @if(!empty($data->$key))
                                        {{ $form['values'][$data->$key] }}
                                    @endif
                                @break
                                @case('datetime')
                                    {{ $data->$key }}
                                @break
                                @case('file')
                                    @if(!empty($data->$key))
                                        @if(preg_match("/^http/",$data->$key))
                                            <img src="{{ $data->$key }}" alt="" width="300"><br>
                                        @else
                                            <img src="{{ env('AWS_URL') }}/user/{{ $data->$key }}" alt="" width="300"><br>
                                        @endif
                                    @endif
                                @break
                                @case('json')
                                @include('admin.parts.form_json')
                                @break
                                @case('password')

                                @break
                                @default
                                    {{ $data->$key }}
                            @endswitch
                            </div>
                        </div>
                        @endforeach

                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                    <a href="{{ action('Admin\\'.$title[1].'Controller@index') }}"
                                       class="btn btn-default">戻る</a>
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
