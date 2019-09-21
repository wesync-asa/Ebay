@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">

            {{--<h3>検索条件設定</h3>--}}
            <div class="row">
                <div class="col-sm-2"><h3>検索条件設定</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>ebayサイト</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <select class="form-control" id="sel1">
                                <option>ebay site 1</option>
                                <option>ebay site 2</option>
                                <option>ebay site 3</option>
                                <option>ebay site 4</option>
                                <option>ebay site 4</option>
                                <option>ebay site 4</option>
                                <option>ebay site 4</option>
                                <option>ebay site 4</option>
                                <option>ebay site 4</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>キーワード</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="keyword" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>出品者ID</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>商品の状態</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <label class="">
                                <input type="checkbox" style="" value="">新品
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="">中古
                            </label>
                            <label class="checkbox-inline">
                                <input type="checkbox" value="">未指定商品
                            </label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>価格（USD)</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" style="width: 48%;display: inline-block;" name="" value="" />  ~
                            <input type="text" class="form-control" style="width: 48%;display: inline-block;" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>在庫数</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" style="width: 48%;display: inline-block;" name="" value="" /> ~
                            <input type="text" class="form-control" style="width: 48%;display: inline-block;" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>ワールドワイト検索</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="checkbox" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>日本発送可能のみ</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="checkbox" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>カテゴリー LEVEL1</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="category_label_1" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>カテゴリー LEVEL2</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="category_label_2" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>カテゴリー LEVEL3</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="category_label_3" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4"></div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary">対象商品数をチェック</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-2"><h3>CSV価格計算</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>加算定額（USD)</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>乗算係数</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>為替レート（円)</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>切り上げ</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <label class="radio-inline"><input type="radio" name="csvradio" checked>10の位</label>
                            <label class="radio-inline"><input type="radio" name="csvradio">100の位</label>
                            <label class="radio-inline"><input type="radio" name="csvradio">1000の位</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-2"><h3>商品画像設定</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>1商品あたりの画像数</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-2"><h3>重ねる画像の<br>指定</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>商品に重ねる画像指定</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary">フアイル選択</button>
                            <span>パス名</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>基準点</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>基準点</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <span style="display: inline-block;">横</span>
                            <span style="display: inline-block;"><input type="text" class="form-control" name="" value="" /></span>
                            <span style="display: inline-block;">ピクセル</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">

                            </div>
                        </div>
                        <div class="col-sm-8">
                            <span style="display: inline-block;">縦</span>
                            <span style="display: inline-block;"><input type="text" class="form-control" name="" value="" /></span>
                            <span style="display: inline-block;">ピクセル</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>重ねる画像の大きさ（倍率)</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-2"><h3>指定した画像を<br>挿入</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>全商品に挿入する画像を指定</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary">フアイル選択</button>
                            <span>パス名</span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>画像を挿入する位置<br>（画像の順番)</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="" value="" />
                            <span>番目</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-2"><h3>画像保存設定</h3></div>
                <div class="col-sm-8">
                    <br><br>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">
                                <h5>画像保存方法</h5>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <label class="radio-inline"><input type="radio" name="imgradio" checked>そのままサーバーへ保存 </label>
                            <label class="radio-inline"><input type="radio" name="imgradio">25MBずつzipファイルにする</label>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-4">
                            <div align="right" for="ebay_site">

                            </div>
                        </div>
                        <div class="col-sm-8">
                            <button type="button" class="btn btn-primary">ファイル生成を開始</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <h3>処理ステータス</h3>
            <div class="form-group" align="right">
                <button type="button" class="btn btn-primary">削除</button>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>選択</th>
                        <th>登録日時</th>
                        <th>ステータス</th>
                        <th>キーワード</th>
                        <th>出品者ID</th>
                        <th>件数</th>
                        <th>画像編集</th>
                        <th>画像保存</th>
                        <th>ダウンロード</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" value=""></td>
                        <td>2019/9/6 13:37</td>
                        <td>未処理</td>
                        <td>vintage vase</td>
                        <td></td>
                        <td>3404</td>
                        <td>有</td>
                        <td> サーバー</td>
                        <td><button type="button" class="btn btn-primary">ダウンロード</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" value=""></td>
                        <td>2019/9/6 13:37</td>
                        <td>未処理</td>
                        <td>vintage vase</td>
                        <td></td>
                        <td>3404</td>
                        <td>有</td>
                        <td> サーバー</td>
                        <td><button type="button" class="btn btn-primary">ダウンロード</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" value=""></td>
                        <td>2019/9/6 13:37</td>
                        <td>未処理</td>
                        <td>vintage vase</td>
                        <td></td>
                        <td>3404</td>
                        <td>有</td>
                        <td> サーバー</td>
                        <td><button type="button" class="btn btn-primary">ダウンロード</button></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" value=""></td>
                        <td>2019/9/6 13:37</td>
                        <td>未処理</td>
                        <td>vintage vase</td>
                        <td></td>
                        <td>3404</td>
                        <td>有</td>
                        <td> サーバー</td>
                        <td><button type="button" class="btn btn-primary">ダウンロード</button></td>
                    </tr>
                </tbody>
            </table>

            </div>
        <div class="col-sm-2"></div>
    </div>
</div>
@endsection
