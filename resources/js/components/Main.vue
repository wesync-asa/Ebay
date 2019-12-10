<template>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>検索条件設定</h3>
            <form @submit="productSearch">
                <div class="row form-group">
                    <label for="sel_site" class="col-sm-4 col-form-label text-md-right">ebayサイト</label>
                    <div class="col-sm-8">
                        <select id="sel_site" class="form-control" v-model="site" name="site">
                            <option value="0">eBay US</option>
                            <option value="2">eBay Canada (English)</option>
                            <option value="3">eBay UK</option>
                            <option value="15">eBay Australia</option>
                            <option value="16">eBay Austria</option>
                            <option value="23">eBay Belgium(French)</option>
                            <option value="71">eBay France</option>
                            <option value="77">eBay Germany</option>
                            <option value="100">eBay Motors</option>
                            <option value="101">eBay Italy</option>
                            <option value="123">eBay Belgium(Dutch)</option>
                            <option value="146">eBay Netherlands</option>
                            <option value="186">eBay Spain</option>
                            <option value="193">eBay Switzerland</option>
                            <option value="201">eBay Hong Kong</option>
                            <option value="203">eBay India</option>
                            <option value="205">eBay Ireland</option>
                            <option value="207">eBay Malaysia</option>
                            <option value="210">eBay Canada (French)</option>
                            <option value="211">eBay Philippines</option>
                            <option value="212">eBay Poland</option>
                            <option value="216">eBay Singapore</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="keyword" class="col-sm-4 col-form-label text-md-right">キーワード</label>
                    <div class="col-sm-8">
                        <input v-model="keyword" type="text" class="form-control" name="keyword" value="" />
                        <span v-if="val_keyword" style="color:red">キーワードを入力してください</span>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="producer_id" class="col-sm-4 col-form-label text-md-right">出品者ID</label>
                    <div class="col-sm-8">
                        <input v-model="seller" type="text" class="form-control" name="producer" value="" />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"><div align="right" for="ebay_site">商品の状態</div></div>
                    <div class="col-sm-8" id="divProductType">
                        <label><input type="checkbox" name="productType[]" v-model="proType1"> 新品</label>
                        <label><input type="checkbox" name="productType[]" v-model="proType2"> 中古</label>
                        <label><input type="checkbox" name="productType[]" v-model="proType3"> 未指定商品</label>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"><div align="right" for="ebay_site">出品形式</div></div>
                    <div class="col-sm-8" id="divProductType">
                        <label><input type="checkbox" name="productType[]" v-model="aucType1"> オークション形式</label>
                        <label style="margin-left:5px"><input type="checkbox" name="productType[]" v-model="aucType2"> 今すぐ購入付オークション形式</label>
                        <label style="margin-left:5px"><input type="checkbox" name="productType[]" v-model="aucType3"> 固定料金形式</label>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right">価格（USD）</label>
                    <div class="col-sm-8">
                        <input v-model="price_from" type="text" class="form-control" style="width: 48%;display: inline-block;" name="price_from" />  ~
                        <input v-model="price_to" type="text" class="form-control" style="width: 48%;display: inline-block;" name="price_to"/>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right">在庫数</label>
                    <div class="col-sm-8">
                        <input v-model="qty_from" type="text" class="form-control" style="width: 48%;display: inline-block;" name="stock_from" value="" /> ~
                        <input v-model="qty_to" type="text" class="form-control" style="width: 48%;display: inline-block;" name="stock_to" value="" />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"><div align="right" for="ebay_site">ワールドワイト検索</div></div>
                    <div class="col-sm-8">
                        <input v-model="worldwide" type="checkbox" name="available" value="worldwide" />
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"><div align="right" for="ebay_site">日本発送可能のみ</div></div>
                    <div class="col-sm-8">
                        <input v-model="japan" type="checkbox" name="available" value="japan" />
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right"><i v-if="this.catLoading1" class="fa fa-refresh fa-spin"></i>カテゴリー LEVEL1</label>
                    <div class="col-sm-8">
                        <select id="sel_category_1" class="form-control" v-model="sel_category_1" name="category_label_1" @change="onCategory1">
                            <option value="">カテゴリを選択してください</option>
                            <template v-for="(cat, index) in top_cats">
                                <option v-bind:key="index" :value="cat.id">{{cat.name}}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right"><i v-if="this.catLoading2" class="fa fa-refresh fa-spin"></i>カテゴリー LEVEL2</label>
                    <div class="col-sm-8">
                        <select id="sel_category_2" class="form-control" v-model="sel_category_2" name="category_label_2" @change="onCategory2">
                            <option value="">カテゴリを選択してください</option>
                            <template v-for="(cat, index) in main_cats">
                                <option v-bind:key="index" :value="cat.id">{{cat.name}}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right"><i v-if="this.catLoading3" class="fa fa-refresh fa-spin"></i>カテゴリー LEVEL3</label>
                    <div class="col-sm-8">
                        <select id="sel_category_3" class="form-control" v-model="sel_category_3" name="category_label_3">
                            <option value="">カテゴリを選択してください</option>
                            <template v-for="(cat, index) in sub_cats">
                                <option v-bind:key="index" :value="cat.id">{{cat.name}}</option>
                            </template>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <button type="submit" class="btn btn-primary">
                            対象商品数をチェック
                            <i v-if="this.proLoading" class="fa fa-refresh fa-spin"></i>
                        </button>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-8">
                        <span v-if="!this.proLoading">商品数 : {{productCt}}</span>
                        <span v-if="!this.proLoading" style="color:red">{{searchStatus}}</span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>CSV価格計算</h3>
            <form class="form-horizontal">
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right">加算定額（USD）</label>
                    <div class="col-sm-6">
                        <input v-model="diff" type="number" class="form-control" name="" value="" min="0.1" max="10.0" step="0.1" />
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right">乗算係数</label>
                    <div class="col-sm-6">
                        <input v-model="multiply" type="number" class="form-control" name="" value="" />
                    </div>
                </div>
                <div class="row form-group">
                    <label for="" class="col-sm-4 col-form-label text-md-right">為替レート（ 円 ）</label>
                    <div class="col-sm-6">
                        <input v-model="exrate" type="number" class="form-control" name="" value="" step="0.1"/>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-sm-4"><div align="right" for="ebay_site">切り上げ</div></div>
                    <div class="col-sm-6">
                        <label><input v-model="unit" type="radio" value="10" checked>10の位</label>
                        <label><input v-model="unit" type="radio" value="100" checked>100の位</label>
                        <label><input v-model="unit" type="radio" value="1000" checked>1000の位</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>商品画像設定</h3>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">1商品あたりの画像数</label>
                <div class="col-sm-6">
                    <input v-model="image_limit" type="number" class="form-control" name="" value="" min="1" max="8" />
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>重ねる画像の指定</h3>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">商品に重ねる画像指定</label>
                <div class="col-sm-6">
                    <input v-on:change="insertPath" type="file" ref="file"/>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">基準点</label>
                <div class="col-sm-6">
                    <select id="ref_point" class="form-control" v-model="ref_point">
                        <option value="top-left">左上</option>
                        <option value="top">上</option>
                        <option value="top-right">右上</option>
                        <option value="left">左</option>
                        <option value="center">中央</option>
                        <option value="right">右</option>
                        <option value="bottom-left">左下</option>
                        <option value="bottom">下</option>
                        <option value="bottom-right">右下</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">基準点からの距離</label>
                <div class="col-sm-8">
                    <span style="display: inline-block;">横</span>
                    <span style="display: inline-block;"><input v-model="off_x" type="number" class="form-control" name="" value="" /></span>
                    <span style="display: inline-block;">ピクセル</span>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right"></label>
                <div class="col-sm-8">
                    <span style="display: inline-block;">縦</span>
                    <span style="display: inline-block;"><input v-model="off_y" type="number" class="form-control" name="" value="" /></span>
                    <span style="display: inline-block;">ピクセル</span>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">重ねる画像の大きさ（倍率）</label>
                <div class="col-sm-6">
                    <input v-model="scale" type="number" class="form-control" name="" value="" />
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>


    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>指定した画像を挿入</h3>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">全商品に挿入する画像を指定</label>
                <div class="col-sm-8">
                    <input v-on:change="addOnPath" type="file" ref="file"/>
                </div>
            </div>
            <div class="row form-group">
                <label for="" class="col-sm-4 col-form-label text-md-right">画像を挿入する位置（画像の順番）</label>
                <div class="input-group col-sm-6" style="align-items:center">
                    <input v-model="addon_pos" type="number" class="form-control" name="" value="" />
                    <span>番目</span>
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>


    <div class="row">
        <div class="col-sm-1"></div>
        <div class="col-sm-8">
            <h3>画像保存設定</h3>
            <div class="row form-group">
                <div class="col-sm-4"><div align="right" for="ebay_site">画像保存方法</div></div>
                <div class="col-sm-8">
                    <label class="radio-inline"><input v-model="image_loc" type="radio" name="imgradio" value="0">そのままサーバーへ保存 </label>
                    <label class="radio-inline"><input v-model="image_loc" type="radio" name="imgradio" value="1">25MBずつzipファイルにする</label>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-4">
                    <div align="right" for="ebay_site">

                    </div>
                </div>
                <div class="col-sm-8">
                    <button v-on:click="process"  type="button" class="btn btn-primary">ファイル生成を開始
                        <i v-if="this.processing" class="fa fa-refresh fa-spin"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-9">
            <h3>処理ステータス</h3>
            <div class="form-group">
                <button type="button" class="btn btn-primary" v-on:click="removeHistory">削除</button>
                <button type="button" class="btn btn-danger pull-right" v-on:click="resetQueue">リセット</button>
            </div>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>選択</th>
                        <th>登録日時</th>
                        <th>処理終了</th>
                        <th>ステータス</th>
                        <th>キーワード</th>
                        <th>出品者ID</th>
                        <th>件数</th>
                        <th>画像編集</th>
                        <th>画像保存</th>
                        <th>ダウンロード</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(item, index) in history">
                        <tr v-bind:key="index">
                            <td>{{item.id}}</td>
                            <td><input v-if="item.status === 'finish' || item.status === 'failure'" type="checkbox"
                                v-model="remove_check"
                                :checked="remove_check.indexOf(+item.id)>-1"
                                :value="item.id"></td>
                            <td>{{item.created_at}}</td>
                            <td v-if="item.status === 'finish' || item.status === 'failure'">{{item.updated_at}}</td>
                            <td v-if="item.status === 'init' || item.status === 'process'"></td>
                            <td v-if="item.status === 'init'">未処理</td>
                            <td v-if="item.status === 'process'">実行中</td>
                            <td v-if="item.status === 'finish'">完了</td>
                            <td v-if="item.status === 'failure'">失敗</td>
                            <td>{{item.keyword}}</td>
                            <td>{{item.seller}}</td>
                            <td>{{item.count}}</td>
                            <td v-if="item.image_edit == 1">有</td>
                            <td v-if="item.image_edit != 1">無</td>
                            <td v-if="item.image_loc === 0">サーバー</td>
                            <td v-if="item.image_loc === 1">zip</td>
                            <td><button v-if="item.status === 'finish'" v-on:click="download(item.id)" type="button" class="btn btn-primary">ダウンロード</button></td>
                            <td><button v-on:click="detail(item)" type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-detail">詳細</button></td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="col-sm-2"></div>
    </div>
    <div class="modal fade in" id="modal-detail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" v-if="current_detail != null">
                    <h5>検索条件設定</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">ebayサイト</label><div class="col-sm-6">{{ current_detail.site_name }}</div>
                        <label class="col-sm-6 text-md-right">キーワード</label><div class="col-sm-6">{{ current_detail.keyword }}</div>
                        <label class="col-sm-6 text-md-right">出品者ID</label><div class="col-sm-6">{{ current_detail.seller }}</div>
                        <label class="col-sm-6 text-md-right">商品の状態</label><div class="col-sm-6">{{ current_detail.proType1 }}</div>
                        <label class="col-sm-6 text-md-right">出品形式</label><div class="col-sm-6">{{ current_detail.aucType1 }}</div>
                        <label class="col-sm-6 text-md-right">価格（USD）</label><div class="col-sm-6">{{ current_detail.price_from }} ~ {{ current_detail.price_to }} </div>
                        <label class="col-sm-6 text-md-right">在庫数</label><div class="col-sm-6">{{ current_detail.qty_from }} ~ {{ current_detail.qty_to }}</div>
                        <label class="col-sm-6 text-md-right">ワールドワイト検索</label><div class="col-sm-6">{{ current_detail.worldwide }}</div>
                        <label class="col-sm-6 text-md-right">日本発送可能のみ</label><div class="col-sm-6">{{ current_detail.japan }}</div>
                        <label class="col-sm-6 text-md-right">カテゴリー</label><div class="col-sm-6">{{ current_detail.category_name }}</div>
                    </div>
                    <h5>CSV価格計算</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">加算定額（USD）</label><div class="col-sm-6">{{ current_detail.diff }}</div>
                        <label class="col-sm-6 text-md-right">乗算係数</label><div class="col-sm-6">{{ current_detail.multiply }}</div>
                        <label class="col-sm-6 text-md-right">為替レート（ 円 ）</label><div class="col-sm-6">{{ current_detail.exrate }}</div>
                        <label class="col-sm-6 text-md-right">切り上げ</label><div class="col-sm-6">{{ current_detail.unit }}</div>
                    </div>
                    <h5>商品画像設定</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">1商品あたりの画像数</label><div class="col-sm-6">{{ current_detail.image_limit }}</div>
                    </div>
                    <h5>重ねる画像の指定</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">商品に重ねる画像指定</label><div class="col-sm-6"><img v-bind:src="current_detail.insert_file" alt=""/></div>
                        <label class="col-sm-6 text-md-right">基準点</label><div class="col-sm-6">{{ current_detail.ref_point_name }}</div>
                        <label class="col-sm-6 text-md-right">基準点からの距離</label><div class="col-sm-6">{{ current_detail.off_x }} : {{ current_detail.off_y }}</div>
                        <label class="col-sm-6 text-md-right">重ねる画像の大きさ（倍率）</label><div class="col-sm-6">{{ current_detail.scale }}</div>
                    </div>
                    <h5>指定した画像を挿入</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">全商品に挿入する画像を指定</label><div class="col-sm-6"><img v-bind:src="current_detail.addon_file" alt=""/></div>
                        <label class="col-sm-6 text-md-right">画像を挿入する位置（画像の順番）</label><div class="col-sm-6">{{ current_detail.addon_pos }}</div>
                    </div>
                    <h5>画像保存設定</h5>
                    <div class="row form-group">
                        <label class="col-sm-6 text-md-right">画像保存方法</label>
                        <div class="col-sm-6" v-if="current_detail.image_loc == '0'">そのままサーバーへ保存</div>
                        <div class="col-sm-6" v-if="current_detail.image_loc == '1'">25MBずつzipファイルにする</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</template>

<script>
    import axios from 'axios';

    export default {
        data() {
            return {
                site: "0",
                productCt: 0,
                proLoading: false,
                processing: false,
                keyword: "",
                seller: "",
                proType1: "",
                proType2: "",
                proType3: "",
                aucType1: "",
                aucType2: "",
                aucType3: "",
                price_from: "",
                price_to: "",
                qty_from: "",
                qty_to: "",
                worldwide: "true",
                japan: "",
                sel_category_1: "",
                sel_category_2: "",
                sel_category_3: "",
                diff: "",
                multiply: "",
                exrate: "",
                unit: "",
                image_limit: "4",
                ref_point: "top-left",
                off_x: "0",
                off_y: "0",
                scale: "100",
                addon_pos: "",
                addon_file: "",
                insert_file: "",
                image_loc: 0,
                history: [],
                remove_check: [],
                top_cats: [],
                main_cats: [],
                sub_cats: [],
                catLoading1: false,
                catLoading2: false,
                catLoading3: false,
                searchStatus: "",
                val_keyword: false,
                current_detail: null,
            }
        },
        mounted() {
            this.updateHistory();
            this.loadTopCategory();
            Echo.channel('queries').listen('QueryChanged', (e) => {
                console.log(this.history);
                var bidx = -1;
                for(var i = 0; i < this.history.length; i++){
                    var item = this.history[i];
                    if (e.query.id == item.id){
                        this.history.splice(i, 1, e.query);
                        bidx = i;
                    }
                }
                if (bidx < 0) {
                    // this.history.add(e.query);
                }
            })
        },
        methods: {
            detail(item){
                console.log(item.condition);
                this.current_detail = item.condition;
            },
            onCategory1(){
                this.catLoading2 = true;
                axios.post("http://" + window.location.hostname + "/api/category",{
                    id: this.sel_category_1
                }).then(response => {
                    this.main_cats = response.data.cats;
                    this.catLoading2 = false;
                }).catch(response => {
                    this.catLoading2 = false;
                })
            },
            onCategory2(){
                this.catLoading3 = true;
                axios.post("http://" + window.location.hostname + "/api/category",{
                    id: this.sel_category_2
                }).then(response => {
                    this.sub_cats = response.data.cats;
                    this.catLoading3 = false;
                }).catch(response => {
                    this.catLoading3 = false;
                })
            },
            loadTopCategory(){
                this.catLoading1 = true;
                axios.post("http://" + window.location.hostname + "/api/category",{
                    id: '-1'
                }).then(response => {
                    this.top_cats = response.data.cats;
                    this.catLoading1 = false;
                }).catch(response => {
                    this.catLoading1 = false;
                })
            },
            removeHistory(){

                axios.post("http://" + window.location.hostname + "/api/remove",{
                    ids: this.remove_check
                }).then(response => {
                    this.updateHistory()
                }).catch(error => {

                })
            },
            resetQueue(){
                var r = confirm("リセットしてよろしいですか?");
                if (r == true){
                    axios.get("http://" + window.location.hostname + "/api/reset")
                    .then(response => {
                        this.updateHistory();
                        alert('リセット完了');
                    }).catch(error => {
                    })
                }
            },
            download(id){
                axios.post("http://" + window.location.hostname + "/api/download", {
                    id: id
                }).then(response => {
                    // window.open("http://blueseason.raindrop.jp/downloads/" + id + "/"+ id +".csv", '_blank');
                    console.log(response);
                    var files = response.data.files;
                    for (var index = 0; index < files.length; index++){
                        console.log(index);
                        window.open(files[index], '_blank');
                    }
                }).catch(error => {

                })
            },
            updateHistory(){
                axios.get("http://" + window.location.hostname + '/api/history').then(response => {
                    this.history = response.data.history
                }).catch(error => {

                })
            },
            addOnPath(e){
                this.addon_file = e.target.files[0];
            },
            insertPath(e){
                this.insert_file = e.target.files[0];
            },
            productSearch(e) {
                e.preventDefault();
                if (this.proLoading){
                    return;
                }
                this.val_keyword = false;
                if (this.keyword == "") {
                    this.val_keyword = true;
                    return;
                }
                this.productCt = 0;
                this.proLoading = true;
                this.searchStatus = "";
                let cat = this.sel_category_1;
                if (this.sel_category_2 != ""){
                    cat = this.sel_category_2;
                }
                if (this.sel_category_3 != ""){
                    cat = this.sel_category_3;
                }
                axios.post("http://" + window.location.hostname + '/api/getProductCount', {
                    site: this.site,
                    keyword: this.keyword,
                    proType1: this.proType1,
                    proType2: this.proType2,
                    proType3: this.proType3,
                    aucType1: this.aucType1,
                    aucType2: this.aucType2,
                    aucType3: this.aucType3,
                    price_from: this.price_from,
                    price_to: this.price_to,
                    qty_from: this.qty_from,
                    qty_to: this.qty_to,
                    worldwide: this.worldwide,
                    japan: this.japan,
                    seller: this.seller,
                    category: cat
                }).then(response => {
                    console.log(response.data);
                    if (response.data.success){
                        this.productCt = response.data.totalEntries;
                    } else {
                        this.searchStatus = response.data.msg[0];
                    }
                    this.proLoading = false;
                }).catch(response => {
                    this.proLoading = false;
                })
            },
            process(e){
                if (this.processing) return;
                if (this.productCt == 0) return;
                this.processing = true;
                let formData = new FormData();
                let cat = this.sel_category_1;
                if (this.sel_category_2 != ""){
                    cat = this.sel_category_2;
                }
                if (this.sel_category_3 != ""){
                    cat = this.sel_category_3;
                }
                //search field
                formData.append('productCt', this.productCt);
                formData.append('site', this.site);
                formData.append('site_name', $('#sel_site option:selected').text());
                formData.append('keyword', this.keyword);
                formData.append('seller', this.seller);
                formData.append('proType1', this.proType1);
                formData.append('proType2', this.proType2);
                formData.append('proType3', this.proType3);
                formData.append('aucType1', this.aucType1);
                formData.append('aucType2', this.aucType2);
                formData.append('aucType3', this.aucType3);
                formData.append('price_from', this.price_from);
                formData.append('price_to', this.price_to);
                formData.append('qty_from', this.qty_from);
                formData.append('qty_to', this.qty_to);
                formData.append('worldwide', this.worldwide);
                formData.append('japan', this.japan);
                formData.append('category', cat);
                var category_names = Array();
                if (this.sel_category_1 != "") category_names.push($('#sel_category_1 option:selected').text());
                if (this.sel_category_2 != "") category_names.push($('#sel_category_2 option:selected').text());
                if (this.sel_category_3 != "") category_names.push($('#sel_category_3 option:selected').text());
                formData.append('category_name', category_names.join("/"));
                //csv field
                formData.append('diff', this.diff);
                formData.append('multiply', this.multiply);
                formData.append('exrate', this.exrate);
                formData.append('unit', this.unit);
                //image field
                formData.append('image_limit', this.image_limit);
                formData.append('ref_point', this.ref_point);
                formData.append('ref_point_name', $('#ref_point option:selected').text());
                formData.append('off_x', this.off_x);
                formData.append('off_y', this.off_y);
                formData.append('scale', this.scale);
                formData.append('addon_pos', this.addon_pos);
                formData.append('addon_file', this.addon_file);
                formData.append('insert_file', this.insert_file);
                formData.append('image_loc', this.image_loc);
                axios.post("http://" + window.location.hostname + '/api/process',
                    formData,
                    {
                        'Content-Type': 'multipart/form-data'
                    }
                ).then(response => {
                    if(response.data.success){
                        this.updateHistory();
                    } else {
                        alert(response.data.message);
                    }
                    this.processing = false;
                }).catch(response => {
                    this.processing = false;
                })
            }
        }
    }
</script>
