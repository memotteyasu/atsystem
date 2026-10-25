<div class="ch-container">
    <div class="row">

<div class="row">
    <div class="box col-md-12">
        <div class="box-inner">
            <div class="well">
                <h1><i class="glyphicon glyphicon-info-sign"></i>　<?php
                $date = New DateTime();
                echo $date->format(h('Y年m月d日')); ?> ご来社スケジュール</h1>

            </div>

            <div class="box-content row">

                <div class="col-lg-12 col-md-12 text-center">
                <table class="table table-striped table-bordered bootstrap-datatable responsive">
                <tbody>
						<?php foreach($receptionmessages as $receptionmessage): ?>
							<tr>
								<td style="font-size:2.5em;width:500px;line-height:100px;">
								<?php
									$date = new DateTime($receptionmessage['Receptionmessage']['receptiondate']);
									echo $date->format(h('H時i分'));
								?>
								</td>
								<td style="font-size:2.5em;line-height:100px;" class="text-left">
								<?php echo h($receptionmessage['Receptionmessage']['receptioncomment']); ?>
								</td>
							</tr>
						<?php endforeach; ?>
				</tbody>
				</table>
				</div>

            </div>

        </div>
    </div>
</div>



    <!-- content ends -->
    </div><!--/#content.col-md-0-->

</div><!--/.fluid-container-->
<script>
setTimeout(function(){
location.reload();
},60000);
</script>
