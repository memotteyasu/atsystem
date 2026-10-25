<?php
	echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js');
	echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js');
?>

<script type="text/javascript">
jQuery(function(){
	jQuery(".sortable").sortable({
		connectWith:".sortable",
		update: function(event, ui){
			console.log($(this).sortable("serialize"));
		}
	});

});
</script>


<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
        <div class="col-sm-2 col-lg-2">

<form action="" method="post">


	<ul class="sortable">
	<?php foreach ($tests as $test): ?>
		<li>
		<div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
		<?php echo ($test['Gridtest']['name']); ?>
		</div>
		</div>
		</div>
		</li>
	<?php endforeach; ?>
	</ul>



    <ul class="sortable">
    	<li id="item_1">
	    <div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
	    item1
	    </div>
	    </div>
	    </div>
		</li>
		<li id="item_2">
	    <div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
	    item2
	    </div>
	    </div>
	    </div>
		</li>
		<li id="item_3">
	    <div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
	    item3
	    </div>
	    </div>
	    </div>
		</li>
		<li id="item_4">
	    <div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
	    item4
	    </div>
	    </div>
	    </div>
		</li>
		<li id="item_5">
	    <div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
	    item5
	    </div>
	    </div>
	    </div>
		</li>
	</ul>

<?php foreach ($tests as $test): ?>
	<ul class="sortable">
		<li id="$test['Gridtest']['id']">
		<div class='box'>
		<div class='box-inner'>
		<div class='box-content'>
		<?php echo ($test['Gridtest']['sort_id']).'　　'.($test['Gridtest']['name']); ?>
		</div>
		</div>
		</div>
		</li>
	</ul>
<?php endforeach; ?>


        </div>
        <!--/span-->
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>

        <div id="content" class="col-lg-10 col-sm-10">
            <!-- content starts -->
                <div>
        <ul class="breadcrumb">
            <li>
                <?php echo $this->Html->link('Home',array('controller'=>'HostTops','action'=>'index')); ?>
            </li>
            <li>
                <a href="#">Tables</a>
            </li>
        </ul>
    </div>

    <div class="row">
    <div class="box col-md-12">
    <div class="box-inner">
    <div class="box-header well" data-original-title="">
        <h2>
                    <div style="float:left;">
                        <select id="selectError" data-rel="chosen">
                            <option>2017年1月1日</option>
                            <option>2017年1月2日</option>
                            <option>2017年1月3日</option>
                            <option>2017年1月4日</option>
                            <option>2017年1月5日</option>
                        </select> から1週間
                    </div>
                    <div class="controls">
                        <select id="selectError" data-rel="chosen">
                            <option>フェノール</option>
                            <option>ブタノール</option>
                            <option>ネオドール</option>
                            <option>サンプレン</option>
                            <option>サクエチ</option>
                        </select>
                    </div>
        </h2>

        <div class="box-icon">
            <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
            <a href="#" class="btn btn-minimize btn-round btn-default"><i
                    class="glyphicon glyphicon-chevron-up"></i></a>
            <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
        </div>
    </div>
    <div class="box-content">
    <table class="table table-striped table-bordered bootstrap-datatable datatable responsive">
    <thead>
    <tr>
        <th>車両番号</th>
        <th>2016年9月1日(木)</th>
        <th>2016年9月2日(金)</th>
        <th>2016年9月3日(土)</th>
        <th>2016年9月4日(日)</th>
        <th>2016年9月5日(月)</th>
        <th>2016年9月6日(火)</th>
        <th>2016年9月7日(水)</th>
    </tr>
    </thead>
    <tbody>

    <?php for ($i = 1; $i <= 8; $i++) {
    	echo '<tr>';
    		echo '<td class="center droppable">'.$i.$i.$i.$i.'</td>';
    		for ($n = 1; $n <= 7; $n++) {
    			echo '<td class="center">
    					<a href="#">オーダー</a>aaa<ul class="sortable"><li></li></ul>
    				</td>';
    		}
    	echo '</tr>';
    }
    ?>

<!--
    <tr>
        <td class="center droppable">1111</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">2222</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">3333</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">4444</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">5555</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">6666</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">7777</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
    <tr>
        <td class="center">8888</td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
        <td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>
    </tr>
-->
    </tbody>
    </table>
    </div>
    </div>
    </div>
    <!--/span-->

    </div><!--/row-->




</div><!--/fluid-row-->
