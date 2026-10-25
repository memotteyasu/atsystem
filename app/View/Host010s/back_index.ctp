<!-- 勤怠照会 -->
<!-- Host010s -->
<div class="ch-container">
    <div class="row">

        <!-- left menu starts -->
<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->

        <div id="content" class="col-lg-10 col-md-10 col-sm-10">
            <!-- content starts -->
			<div>
				<ul class="breadcrumb">
					<li>
						<?php
							$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
							echo $this->element('breadcrumbs');
						?>
						<?php echo $this->Flash->render(); ?>
					</li>
				</ul>
			</div>

			<?php
				echo $this->element('searchForm2', [
					'model' => 'Kintaitran',
					'url' => ['controller'=>'Host010s','action'=>'index'],
					'text' => '営業所　',
					'input' => 'branchmasters_id',
					'text2' => '部署　',
					'input2' => 'departmentmasters_id',
				]);
			?>


		    <div class="row">
			    <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
				    <div class="box-inner">
					    <div class="box-header well">
					        <h2>勤怠照会（2018年09月）</h2>
					    </div>
					    <div class="box-content">
				    	<table class="table table-striped table-bordered bootstrap-datatable">
				    		<thead>
							    <tr>
							    <?php
							    $i=0;
							    while($i<=31) { ?>
								    	<th><?php if($i==0){}else{echo $i;} ?></th>
							        <?php
							        $i++;
							        } ?>
							    </tr>
						</thead>
						<tbody>
							    <tr>
							    <td>間部卓也</td>
							    <?php
							    $i=1;
							    while($i<=31){
								    switch($i){
								      case 2:
								      case 16:
								      case 17:
								      case 23:
								      case 24:
									    print('<td><a href="" class="a_block"> </a></td>');
									    break;
								      case 9:
								        print('<td style="color:#ff0000;"><a href="/Host011s" class="a_block">休出</a></td>');
									    break;
								      case 12:
								        print('<td style="background-color:#ffff00;"><a href="#" class="a_block">有休</a></td>');
									    break;
								      default:
								        print('<td style="background-color:#99ccff;"><a href="#" class="a_block">出</a></td>');
									    break;
								    }
								    $i++;
							    } ?>
							    </tr>
							    <tr>
							    <td>西川満</td>
							    <?php
							    $i=1;
							    while($i<=31){
								    switch($i){
								      case 2:
								      case 16:
								      case 17:
								      case 23:
								      case 24:
									    print('<td><a href="" class="a_block"> </a></td>');
									    break;
								      case 9:
								        print('<td style="color:#ff0000;"><a href="#" class="a_block">休出</a></td>');
									    break;
								      case 12:
								        print('<td style="background-color:#ffff00;"><a href="#" class="a_block">有休</a></td>');
									    break;
								      default:
								        print('<td style="background-color:#99ccff;"><a href="#" class="a_block">出</a></td>');
									    break;
								    }
								    $i++;
							    } ?>
							    </tr>
							    <tr>
							    <td>青木淳司</td>
							    <?php
							    $i=1;
							    while($i<=31){
								    switch($i){
								      case 2:
								      case 16:
								      case 17:
								      case 23:
								      case 24:
									    print('<td><a href="" class="a_block"> </a></td>');
									    break;
								      case 9:
								        print('<td style="color:#ff0000;"><a href="#" class="a_block">休出</a></td>');
									    break;
								      case 12:
								        print('<td style="background-color:#ffff00;"><a href="#" class="a_block">有休</a></td>');
									    break;
								      default:
								        print('<td style="background-color:#99ccff;"><a href="#" class="a_block">出</a></td>');
									    break;
								    }
								    $i++;
							    } ?>
							    </tr>
					    </tbody>
					</table>
					</div>
				    </div>
			    </div>
		    </div>

			    <?php // echo $this->element('pager')?>

				<p>
				</p>
			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->

	</div><!--/fluid-row-->

</div><!--/.fluid-container-->