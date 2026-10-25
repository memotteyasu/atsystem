<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/jquery-ui.min.js"></script>
<link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/black-tie/jquery-ui.css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery-ui-i18n.min.js"></script>

<script type="text/javascript">
jQuery.noConflict();
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
		});
});
</script>


<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->


        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
            <div>
    <ul class="breadcrumb">
        <li>
            <a href="Tests/index">Home</a>
        </li>
        <li>
            <a href="#">受注登録（化学品）</a>
        </li>
    </ul>
</div>
<?php echo $this->Form->create('Ordertran'); ?>

<div class="row">
<!-- left start -->
    <div class="box col-md-4">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-info-sign"></i>受注登録（化学品）</h2>

            </div>

<!-- 荷主選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">荷主</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen" style="width:150px;">
                            <option>TSトランス</option>
                            <option>日本油脂</option>
                            <option>日本化成</option>
                            <option>化学物流</option>
                            <option>商事ケミ</option>
                            <option>BJL</option>
                            <option>JSP</option>
                            <option>MCI</option>
                            <option>SK</option>
                            <option>三菱商事</option>
                            <option>信越大阪</option>
                            <option>中部液輸</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /荷主選択 -->
<!-- 商品選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">商品</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen" style="width:150px;">
                            <option>POP FA702</option>
                            <option>POP FA728R</option>
                            <option>POP FA952</option>
                            <option>フェノール</option>
                            <option>PPG 2043-2</option>
                            <option>PPG 38C</option>
                            <option>PPG A-4010</option>
                            <option>アセトン</option>
                            <option>SM</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /商品選択 -->
<!-- 車両選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">車両</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen" style="width:150px;">
                            <option>A1111</option>
                            <option>A2222</option>
                            <option>B1111</option>
                            <option>B2222</option>
                            <option>C3333</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /車両選択 -->
<!-- 積み地選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">積み地</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen">
                            <option>SK</option>
                            <option>東亜</option>
                            <option>BJL</option>
                            <option>松山</option>
                            <option>東邦液化</option>
                            <option>丸善</option>
                            <option>協和</option>
                            <option>東ソー</option>
                            <option>ケミカル</option>
                            <option>辰巳照会</option>
                            <option>鈴与清水</option>
                            <option>三井</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /積み地選択 -->
<!-- 納入先選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">納入先</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen">
                            <option>上田石灰　大垣</option>
                            <option>上野製薬　四日市</option>
                            <option>宇都宮化工　宇都宮</option>
                            <option>宇部三菱セメント　尼崎</option>
                            <option>宇部三菱セメント　赤穂</option>
                            <option>宇部三菱セメント　富山</option>
                            <option>宇部三菱セメント　水島</option>
                            <option>宇部三菱セメント　空見</option>
                            <option>宇部三菱セメント　名古屋</option>
                            <option>ヴィテック　四日市</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /納入先選択 -->
<!-- 納入日選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">納入日時</label>
                    <div class="controls">
                    <input type="text" class="datepicker" style="float:left;">
                    <?php
    echo $this->Form->input('',array(
        'type' => 'time',
        'timeFormat' => '24'
    ));
                         ?>
                    </div>
                </div>
			</div>
<!-- /納入日選択 -->
            <div class="box-content">
	            <a class="btn btn-info" href="#">
	                <i class="glyphicon glyphicon-edit icon-white"></i>
	                登録
	            </a>
	            <a class="btn btn-danger" href="#">
	                <i class="glyphicon glyphicon-trash icon-white"></i>
	                削除
	            </a>
	        </div>
        </div>
    </div>
<!-- left end -->
<!-- right start -->
    <div class="box col-md-8">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-info-sign"></i>工程</h2>
            </div>
<!-- 工程 -->
            <div class="box-content">
                <div class="control-group">
                    <div class="controls">
                    <ul>
                    <li style="padding-bottom:5px;">2016/11/30 05:00 【出庫】　本社<span class="label label-danger">削除</span></li>
                    <li>2016/11/30 06:15 【積み】　○○会社 <span class="label label-danger">削除</span></li>
                    <li>2016/11/30 10:00 【卸し】　☓☓商事 <span class="label label-danger">削除</span></li>
                    <li>2016/11/30 15:00 【帰庫】　本社 <span class="label label-danger">削除</span></li>
                    <li>2016/11/30 15:30 【洗浄】　<span class="label label-danger">削除</span></li>
                    </ul>
                    </div>
                </div>
			</div>
<!-- /工程 -->
        </div>
    </div>
<!-- right end -->
<!-- right under start -->
    <div class="box col-md-8">
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-info-sign"></i>工程登録</h2>
            </div>
<!-- 日時選択 -->
            <div class="box-content">
                <div class="control-group">
                    <div class="controls">
                    <input type="text" class="datepicker" style="float:left;">
                    <?php
    echo $this->Form->input('',array(
        'type' => 'time',
        'timeFormat' => '24'
    ));
                         ?>
                    </div>
                </div>
            </div>
<!-- /日時選択 -->
<!-- 作業選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">作業内容</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen" style="width:100px;">
                            <option>出庫</option>
                            <option>積み</option>
                            <option>卸し</option>
                            <option>帰庫</option>
                            <option>洗浄</option>
                            <option>★作業</option>
                        </select>
                        <input type="text" name="xx1" class="form-control" placeholder="作業内容を入力">
                    </div>
                </div>
			</div>
<!-- /作業選択 -->
<!-- 場所選択 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">場所</label>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen" style="width:100px;">
                            <option>　</option>
                            <option>本社</option>
                            <option>四日市</option>
                        </select>
                    </div>
                </div>
			</div>
<!-- /場所選択 -->
<!-- 積み地納入先 -->
            <div class="box-content">
                <div class="control-group">
                    <label class="control-label" for="selectError">積み地、納入先</label>
                    <div class="controls">
                    東邦液化　→　宇都宮化工　宇都宮
                    </div>
                </div>
			</div>
<!-- /積み地納入先 -->
            <div class="box-content">
	            <a class="btn btn-info" href="#">
	                <i class="glyphicon glyphicon-edit icon-white"></i>
	                登録
	            </a>
	        </div>

        </div>
    </div>
<!-- right under end -->
</div>


    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->

    <hr>

</div><!--/.fluid-container-->


