@extends('admin.layouts.app')

@section('header')
    @include('admin.include.header')
@endsection('header')

@section('content')
    <section class="content-header">
        <h1>
            不動産管理
        </h1>
        <ol class="breadcrumb">
            <!-- li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li-->
        </ol>
    </section>
<div id="building">
    <section class="content" style="min-width: 960px;">

        <div class="content-fluid" style="overflow: auto">

            <div class="col-sm-4">

                <form>
                    <div class="row">
                        <div class="row" style="margin-left: 20px;">
                            <div class="col-sm-6"><label for="name">ビル⼀覧</label></div>
                            <div class="col-sm-6" align="right"><h5>@{{ buildings.length }}棟</h5></div>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;">
                            <table border="0" width="100%">
                                <tr>
                                    <td>
                                        <table border="1" width="100%" >
                                            <tr>
                                                <td align="center" width="25%">コード</td>
                                                <td align="center" width="75%">ビル名</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="width:100%; height:150px; overflow:auto;">
                                            <table border="1" width="100%" >
                                                <tr v-for="(item, key) in buildings">
                                                    <td align="center" width="25%"><a href="##" v-on:click="setBuilding(key)">@{{ item.id }}</a></td>
                                                    <td align="center" width="75%">@{{ item.name }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <br>
                        <div class="row" style="margin-left: 10px;">
                            <a class="btn btn-primary btn-sm" href="#">新規ビル作成</a>
                        </div>
                    </div>
                </form>

                <br>

                <form>
                    <div class="row">
                        <div class="row" style="margin-left: 20px;">
                            <div class="col-sm-6"><label for="name">部屋⼀覧</label></div>
                            <div class="col-sm-6" align="right"><h5>@{{ rooms.length }}件</h5></div>
                        </div>
                        <div class="row" style="margin-left: 10px;">
                            <a class="btn btn-primary btn-sm" href="#">全済み</a>
                            <a class="btn btn-primary btn-sm" href="#">全解除</a>
                            <a class="btn btn-primary btn-sm" href="#">空きチェック</a>
                        </div>
                        <div class="row" style="margin-left: 10px;">
                            降順<input type="checkbox" name="ordercheck"/>
                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;">
                            <table border="0" width="100%">
                                <tr>
                                    <td>
                                        <table border="1" width="100%" >
                                            <tr>
                                                <td align="center" width="5%">*</td>
                                                <td align="center" width="10%">階数</td>
                                                <td align="center" width="10%">号室</td>
                                                <td align="center" width="20%">坪数</td>
                                                <td align="center" width="10%">空</td>
                                                <td align="center" width="45%">用途</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="width:100%;">
                                            <table border="1" width="100%" >
                                                <tr v-for="item in rooms">
                                                    <td align="center" width="5%"><input type="checkbox" name="chk"></td>
                                                    <td align="center" width="10%">@{{ item.floor_cnt }}</td>
                                                    <td align="center" width="10%">@{{ item.room_num }}</td>
                                                    <td align="center" width="20%">@{{ item.tubo_cnt }}</td>
                                                    <td align="center" width="10%">@{{ item.aki }}</td>
                                                    <td align="center" width="45%">@{{ item.state }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="row"><br></div>
                        <div class="row" style="margin-left: 10px;">
                            <a class="btn btn-primary btn-sm" href="#">新規部屋作成</a>
                            <a class="btn btn-primary btn-sm " href="#">部屋削除</a>
                        </div>
                        <div class="row" style="margin-left: 10px;">
                            <a class="btn btn-primary btn-sm" href="#">部屋コピー</a>
                        </div>
                        <div class="row" style="margin-left: 10px;">
                            <a class="btn btn-primary btn-sm" href="#">部屋並び替え</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-8">

                <form>
                    <div class="row" style="background-color: #ECF0D9">
                        <div class="row col-md-offset-0">
                            <label for="name" class="col-md-2">物件情報</label>
                        </div>
                        <div class="row col-md-offset-0">

                            <div class="col-sm-7">

                                <div class="form-group{{ $errors->has('build_name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-2">18326</label>

                                    <div class="col-md-10">
                                        <input id="build_name" type="text" name="build_name" value="秋葉原村井" style="width: 100%">

                                        @if ($errors->has('build_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('build_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('build_date') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-2">築⼯</label>

                                    <div class="col-md-10">
                                        <input id="build_date" type="text" name="build_date" value="1987/9" style="width: 100%">

                                        @if ($errors->has('build_date'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('build_date') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="col-sm-3"><img src="{{ asset('/bower_components/adminLTE/dist/img/avatar.png') }}" style="width: 60px; height: 60px;"></div>
                                <div class="col-sm-3"><img src="{{ asset('/bower_components/adminLTE/dist/img/avatar.png') }}" style="width: 60px; height: 60px;"></div>
                                <div class="col-sm-6"><h5>全2件</h5></div>
                            </div>
                        </div>
                    </div>
                </form>

                <br>

                <form>
                    <div class="row" style="background-color: #ECF0D9">

                        <div class="col-sm-7">
                            <div class="row">
                                <div class="form-group">
                                    <label for="name" class="col-md-3">A-A3_026</label>
                                </div>
                            </div>

                            <div class="row">
                                <label for="name" class="col-md-3">所在地</label>
                                <select class="browser-default custom-select">
                                    <option selected>select menu</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <input type="text" name="addr_name" value="This is address.">
                            </div>

                            <div class="row col-md-offset-0">
                                <input type="radio" name="radio_station" class="col-sm-1">
                                <input type="text" name="station1" value="station1" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">駅</label>
                                <input type="text" name="walk_min1" value="walk_min" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">分</label>
                            </div>
                            <div class="row col-md-offset-0">
                                <input type="radio" name="radio_station" class="col-sm-1">
                                <input type="text" name="station1" value="station1" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">駅</label>
                                <input type="text" name="walk_min1" value="walk_min" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">分</label>
                            </div>
                            <div class="row col-md-offset-0">
                                <input type="radio" name="radio_station" class="col-sm-1">
                                <input type="text" name="station1" value="station1" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">駅</label>
                                <input type="text" name="walk_min1" value="walk_min" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">分</label>
                            </div>
                            <div class="row col-md-offset-0">
                                <input type="radio" name="radio_station" class="col-sm-1">
                                <input type="text" name="station1" value="station1" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">駅</label>
                                <input type="text" name="walk_min1" value="walk_min" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">分</label>
                            </div>
                            <div class="row col-md-offset-0">
                                <input type="radio" name="radio_station" class="col-sm-1">
                                <input type="text" name="station1" value="station1" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">駅</label>
                                <input type="text" name="walk_min1" value="walk_min" class="col-sm-3">
                                <label class="col-sm-2" style="text-align: left">分</label>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="name" class="col-md-4">基準階坪数</label>
                                    <input type="text" name="std_square" value="69坪" class="col-md-2">
                                    <label for="name" class="col-md-1">階</label>
                                    <input type="text" name="floor1" value="1" class="col-md-2">
                                    <label for="name" class="col-md-1">~</label>
                                    <input type="text" name="floor2" value="2" class="col-md-2">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="name">EV数</label>
                                    <select class="browser-default custom-select" class="col-md-3">
                                        <option selected>select menu</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="name">駐⾞台数</label>
                                    <select class="browser-default custom-select" class="col-md-3">
                                        <option selected>select menu</option>
                                        <option value="1">One</option>
                                        <option value="2">Two</option>
                                        <option value="3">Three</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="name">特選物件</label>
                                    <input type="checkbox" name="chk_sel">
                                </div>
                                <div class="col-sm-6">
                                    <label for="name">エリアおすすめ</label>
                                    <input type="checkbox" name="chk_area">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-5">
                            <div class="row">
                                <label for="name">ビル備考</label>
                            </div>
                            <div class="row">
                                <textarea style="width: 100%" name="remarks">dsfdadfsafsadfsdfsfds</textarea>
                            </div>
                            <div class="row">
                                <label for="name">基準階推定底値</label>
                            </div>
                            <div class="row">
                                <input type="text" name="bottom_price" value="bottom price">
                            </div>
                        </div>

                    </div>
                </form>

                <br>

                <form>
                    <div class="row" style="background-color: #ECF0D9">

                        <div class="row col-sm-offset-0">
                            <div class="col-sm-3 col-sm-offset-0 col-sm-padding-0"><input type="text" name="" class="col-sm-9"><label>階</label></div>
                            <div class="col-sm-3"><input type="text" name="" class="col-sm-9"><label>屋</label></div>
                            <div class="col-sm-3"><input type="text" name="" class="col-sm-9"><label>坪</label></div>
                            <div class="col-sm-3"><input type="text" name="" class="col-sm-9"><label>㎡</label></div>
                        </div>

                        <div class="row col-sm-offset-0">

                            <div class="col-sm-3">
                                <select class="browser-default custom-select" class="col-md-2">
                                    <option selected>select menu</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-sm-5">
                                <label for="name">NET</label>
                                <select class="browser-default custom-select" class="col-md-2">
                                    <option selected>select menu</option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>

                        <div class="row col-sm-offset-0">
                            <div class="col-sm-2">
                                <label>済み</label><input type="checkbox" name="chk">
                            </div>
                            <div class="col-sm-6" style="padding-left:0">
                                <label class="col-sm-1">⼊</label><input type="text" name="" class="col-sm-2">
                                <label class="coo_sm-1"></label>
                                <a class="btn btn-primary btn-sm" href="#">即</a>
                                <a class="btn btn-primary btn-sm" href="#">相</a>
                            </div>
                        </div>

                        <div class="row col-sm-offset-0">
                            <div class="col-sm-2">
                                <label>定借</label><input type="checkbox" name="chk">
                            </div>
                            <div class="col-sm-4" style="padding-left: 0">
                                <label for="name" class="col-md-6">契約年数</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-4" style="padding-left: 0">
                                <label for="name" class="col-md-6">解約予告</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-2">
                                <a class="btn btn-primary btn-sm" href="#">更新履歴</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <label class="col-sm-3">敷⾦</label><input type="text" name="" class="col-sm-3">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label class="col-md-6">保証⾦@</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="" class="col-sm-6">&nbsp;
                                <a class="btn btn-primary btn-sm" href="#">未定・相談</a>
                            </div>
                            <div class="col-sm-4">
                                <label for="name" class="col-md-6">保証⾦/㎡</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label class="col-md-6">賃料@</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="" class="col-sm-6">&nbsp;
                                <a class="btn btn-primary btn-sm" href="#">未定・相談</a>
                            </div>
                            <div class="col-sm-4">
                                <label for="name" class="col-md-6">賃料/㎡</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label class="col-md-6">共益費@</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" name="" class="col-sm-6">&nbsp;
                                <a class="btn btn-primary btn-sm" href="#">未定・相談</a>
                            </div>
                            <div class="col-sm-4">
                                <label for="name" class="col-md-6">共益費/㎡</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-4">
                                <label class="col-md-6">推定底値</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-6">
                                <a class="btn btn-primary btn-sm" href="#">基準階底値へコピー </a>
                            </div>
                        </div>

                        <div class="row col-sm-offset-0">
                            <div class="col-sm-12">
                                <input type="text" name="" class="col-sm-10">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <label class="col-md-4">フロア</label>
                                <select class="browser-default custom-select col-md-3">
                                    <option selected></option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                                <input type="text" name="" class="col-sm-3">
                            </div>
                            <div class="col-sm-3">
                                <label class="col-md-6">天高</label>
                                <input type="text" name="" class="col-sm-6">
                            </div>
                            <div class="col-sm-4">
                                <label class="col-md-8">エアコン</label>
                                <select class="browser-default custom-select col-md-4">
                                    <option selected></option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-5">
                                <label class="col-md-4">更新料</label>
                                <select class="browser-default custom-select col-md-3">
                                    <option selected></option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label class="col-md-6">償却</label>
                                <select class="browser-default custom-select col-md-6">
                                    <option selected></option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="col-md-8">礼⾦</label>
                                <select class="browser-default custom-select col-md-4">
                                    <option selected></option>
                                    <option value="1">One</option>
                                    <option value="2">Two</option>
                                    <option value="3">Three</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="col-sm-4">
                                    <label>駐⾞場情報</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" value="adfadsfadsf">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <textarea name="" class="col-sm-12">sadsadsadsad</textarea>
                            </div>
                        </div>

                    </div>
                </form>

                <br>

                <form>
                    <div class="row" style="background-color: #ECF0D9">
                        <div class="row col-sm-6 col-sm-offset-0">
                            <input type="radio" name="rad">ビル単位
                            <input type="radio" name="rad">部屋単位
                            <a class="btn btn-primary btn-sm" href="#">オーナー並び替え</a>
                        </div>
                        <div class="row">

                        </div>
                        <div class="row" style="margin-left: 10px;margin-right: 10px;">
                            <table border="0" width="100%">
                                <tr>
                                    <td>
                                        <table border="1" width="100%" >
                                            <tr>
                                                <td align="center" width="2%"><label>B</label></td>
                                                <td align="center" width="2%"><label>R</label></td>
                                                <td align="center" width="4%"><label>区分</label></td>
                                                <td align="center" width="15%"><label>企業名</label></td>
                                                <td align="center" width="2%"></td>
                                                <td align="center" width="9%"><label>取引態様</label></td>
                                                <td align="center" width="16%"><label>営業所・内線</label></td>
                                                <td align="center" width="12%"><label>担当者</label></td>
                                                <td align="center" width="12%"><label>TEL</label></td>
                                                <td align="center" width="9%"><label>⼿数料</label></td>
                                                <td align="center" width="2%"><label>空</label></td>
                                                <td align="center" width="13%"><label>更新⽇</label></td>
                                                <td align="center" width="2%"><label>D</label></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="width:100%;">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-sm-6">


                    </div>
            </div>
            </form>
        </div>

        </div>

    </section>
    </div>
    <script src="{{ asset('js/vue.min.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@endsection('content')

@section('footer')
    @include('admin.include.footer')
@endsection('footer')
