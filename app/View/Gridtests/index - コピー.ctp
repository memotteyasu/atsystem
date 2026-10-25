<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->

        <noscript>
            <div class="alert alert-block col-md-12">
                <h4 class="alert-heading">Warning!</h4>

                <p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a>
                    enabled to use this site.</p>
            </div>
        </noscript>
        <!-- left menu ends -->

		<div id="content" class="col-lg-10 col-md-10 col-sm-10">
			<!-- content starts -->
			            <div>
						    <ul class="breadcrumb">
						        <li>
						            <?php echo $this->Html->link('Home',['controller'=>'HostTops','action'=>'index']); ?>
						        </li>
						        <li>
						            <?php echo $this->Html->link('化学品配車週間',['action'=>'index']); ?>
						        </li>
						    </ul>
			            </div>

			<!-- /荷主一覧 -->
			    <div class="row">
				    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
					    <div class="box-inner">
						    <div class="box-header well">
						        <h2>
									<?php echo $this->Form->create(false); ?>
									<?php
									     echo $this->Form->input('selectym1', [
									     	'div'=>false,
									     	'label'=>false,
									        'type' => 'date',
									        'dateFormat' => 'YMD',
									        'monthNames' => false,
									        'minYear' => date('Y')-1,
									        'maxYear' => date('Y')+1,
									        'onchange'=>'this.form.submit()',
									        //'data-rel'=>'chosen'
									     ]);
									?>
									から1週間
									<?php
										echo $this->Form->input('productmasters_id',[
											'label'=>false,
											'type'=>'select',
											'div'=>false,
											'class'=>false,
											'onchange'=>'this.form.submit()',
											//'data-rel'=>'chosen',
										]);
									?>

									<?php echo $this->Form->end(); ?>
									<?php //echo $this->Form->submit('左記期間のデータを表示'); ?>
						        </h2>
						    </div>
						    <div class="box-content">
						    	<table class="table table-striped table-bordered bootstrap-datatable">
						    		<thead>
						    			<!--
									    <tr>
									        <th><?php echo $this->Paginator->sort('', '車両番号'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippername', '荷主名称'); ?></th>
									        <th><?php echo $this->Paginator->sort('shippershortname', '荷主略称'); ?></th>
									        <th><?php echo $this->Paginator->sort('departmentmasters_id', '部署名称'); ?></th>
									        <th> </th>
									    </tr>
									    -->
									    <tr>
									        <th>車両番号</th>

									        <?php
									        	$date = new DateTime($paramsDate);

									        	for ($i = 1; $i <= 7; $i++) {
									        		echo '<th>';
									        			if ($i == 1) {
									        				echo $date->format('Y年m月d日').'('.$week[$date->format('w')].')';
									        			} else {
									        				echo $date->modify('+1 day')->format('Y年m月d日').'('.$week[$date->format('w')].')';
									        			}
									        		echo '</th>';
									        	}

									        ?>

											<!--
											<th><?php echo date('Y年m月d日',$firstDate).'('.$week[date('w', $firstDate)].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 1 days',$firstDate)).'('.$week[date('w', strtotime('+ 1 days',$firstDate))].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 2 days',$firstDate)).'('.$week[date('w', strtotime('+ 2 days',$firstDate))].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 3 days',$firstDate)).'('.$week[date('w', strtotime('+ 3 days',$firstDate))].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 4 days',$firstDate)).'('.$week[date('w', strtotime('+ 4 days',$firstDate))].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 5 days',$firstDate)).'('.$week[date('w', strtotime('+ 5 days',$firstDate))].')'; ?></th>
									        <th><?php echo date('Y年m月d日',strtotime('+ 6 days',$firstDate)).'('.$week[date('w', strtotime('+ 6 days',$firstDate))].')'; ?></th>
									        -->
									    </tr>
								    </thead>
								    <tbody>
									    <!--
								    	<?php for ($i = 1; $i <= 8; $i++) {
									    	echo '<tr>';
									    		echo '<td class="center droppable">'.$i.$i.$i.$i.'</td>';
									    		for ($n = 1; $n <= 7; $n++) {
									    			echo '<td class="center"><a href="#">オーダー</a><ul class="sortable"><li></li></ul></td>';
									    		}
									    	echo '</tr>';
									    }
									    ?>
									    -->
								    	<?php foreach ($tests as $test): ?>
								    	<?php //echo debug($test); ?>
									    <tr>
									    	<td><?php echo ($test['Productmaster']['Carmaster']['0']['carno']); ?></td>
									    	<!--
									    	<td><?php echo ($test['Gridtest']['name']); ?></td>
									        <td><?php echo ($test['Productmaster']['pname']); ?></td>
									        <td><?php echo ($test['Gridtest']['orderdate']); ?></td>
									        -->
									        <td>
									        	<?php
									        		$date = new DateTime($paramsDate);
									        		//echo debug($a);
									        		if ($test['Gridtest']['orderdate'] == $date->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',$firstDate)) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 1 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 2 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 3 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 4 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 5 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <td>
									        	<?php
									        		if ($test['Gridtest']['orderdate'] == $date->modify('+1 day')->format('Y-m-d')) {
									        			echo ($test['Gridtest']['name']);
									        		}

									        		/*
									        		if ($test['Gridtest']['orderdate'] == date('Y-m-d',strtotime('+ 6 days',$firstDate))) {
									        			echo ($test['Gridtest']['name']);
									        		}
									        		*/
									        	?>
									        </td>
									        <!--
									        <td>
												<?php echo $this->Html->link(('修正'), array('action' => 'edit',$test['Gridtest']['id']), array('class'=>array('btn btn-info'))); ?>
												<?php echo $this->Form->postLink(('削除'), array('action' => 'delete', $test['Gridtest']['id']), array('confirm' => __('削除しますか？'), 'class'=>array('btn btn-danger'))); ?>
											</td>
											-->
									    </tr>
									    <?php endforeach; ?>
								    </tbody>
								</table>
							</div>
					    </div>
				    </div>
			    </div>

			    <?php //echo $this->element('pager')?>

				<p>
				<?php echo $this->Html->link(('新規登録'), array('action' => 'add'), array('class'=>array('btn btn-success'))); ?>
				</p>

			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

    <footer class="row">
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->
