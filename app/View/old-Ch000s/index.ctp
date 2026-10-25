<!-- 受注管理状況 -->
<!-- Ch000s -->
<style>
    #calendar {
        max-width: 100%;
        margin: 10px auto;
        padding: 0 10px;
    }
</style>

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


荷主　<select name="a">
<option value="A0100">三菱商事</option>
<option value="B0100">辰巳照会</option>
<option value="B0200">辰巳照会（20日締）</option>
<option value="B0300">辰巳照会（末締卸）</option>
<option value="B0400">辰巳照会（末締積）</option>
<option value="C0100">BASFジャパン</option>
<option value="D0100">日本コンセプト</option>
</select>

商品　<select name="a">
<option value="1">PO</option>
<option value="2">POP</option>
<option value="3">フェノール</option>
</select>

    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-calendar"></i> 受注状況</h2>
                </div>
                <div class="box-content">
                    <div id="calendar"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div><!--/row-->


			<!-- content ends -->
	    </div><!--/#content.col-lg-0-->
	</div><!--/fluid-row-->

    <footer class="row">
    </footer>

</div><!--/.fluid-container-->


<script>
jQuery(document).ready(function() {
	jQuery('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		timeFormat: 'HH:mm',
		timezone: 'Asia/Tokyo',
		theme: 'bootstrap3',
		selectHelper: true,
		height: 'auto',
		selectMinDstance: 1,
		editable: true,
		slotEventOverlap: false,
		//eventOrder: ['-color','title'],
		firstDay: 1,
        eventSources: [
			{
				url: '<?php echo $this->Html->url(['controller'=>'Ch00s', 'action'=>'fcfeed']); ?>'
			},
            {
            	url: '<?php echo $this->Html->url(['controller'=>'Ch00s', 'action'=>'fcholiday']); ?>',
            	editable: false,
            }
        ],
		eventClick: function(calEvent,jsEvent,view){
		},
		/*
        dayClick    : function (date, jsEvent, view) {
            url = '<?php echo $this->Html->url(array('controller'=>'Ordertrans', 'action'=>'add')); ?>'+'/'+date.format('Y')+'/'+date.format('M')+'/'+date.format('D'),
            location.href = url
        },
		eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
			wyy=event.start.format("Y"),
			wmm=event.start.format("M"),
			wdd=event.start.format("D"),
			url2 = '<?php echo $this->Html->url(array('controller'=>'ManagerTops', 'action'=>'fcfeed2')); ?>'+'/'+event.id+'/'+wyy+'/'+wmm+'/'+wdd,
			location.href = url2
		},
		*/
    });
});
</script>
