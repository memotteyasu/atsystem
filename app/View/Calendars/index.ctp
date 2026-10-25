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
						            <?php echo $this->Html->link('Home',array('controller'=>'HostTops','action'=>'index')); ?>
						        </li>
						        <li>
						            <?php echo $this->Html->link('カレンダー',array('action'=>'index')); ?>
						        </li>
						        <li>
						        	<?php echo $this->Html->link('追加',array('action'=>'add')); ?>
						        </li>
						    </ul>
			            </div>

    <div class="row">
        <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><i class="glyphicon glyphicon-calendar"></i> Calendar</h2>
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
        <p class="col-lg-12 col-sm-12 powered-by">Powered by: Asahi-Trans</p>
    </footer>

</div><!--/.fluid-container-->

<?php //debug($reservetrans); ?>

<script>
jQuery(document).ready(function() {
	jQuery('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay,listMonth'
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
            	//url: '<?php echo $this->Html->url(array('controller'=>'ManagerTops', 'action'=>'fcfeed')); ?>'
            },
            {
            	url: '<?php echo $this->Html->url(array('controller'=>'Calendars', 'action'=>'fcholiday')); ?>',
            	editable: false,
            }
        ],
		eventClick: function(calEvent,jsEvent,view){
		},
		/*
        dayClick    : function (date, jsEvent, view) {
            //url = '/schedule/HostReserves/add/'+date.format('Y')+'/'+date.format('M')+'/'+date.format('D'),
            url = '<?php echo $this->Html->url(array('controller'=>'ManagerReserves', 'action'=>'add')); ?>'+'/'+date.format('Y')+'/'+date.format('M')+'/'+date.format('D'),
            location.href = url
        },
		eventDrop: function(event, delta, revertFunc, jsEvent, ui, view) {
			wyy=event.start.format("Y"),
			wmm=event.start.format("M"),
			wdd=event.start.format("D"),
			//url2 = '/schedule/HostTops/fcfeed2/'+event.id+'/'+wyy+'/'+wmm+'/'+wdd,
			url2 = '<?php echo $this->Html->url(array('controller'=>'ManagerTops', 'action'=>'fcfeed2')); ?>'+'/'+event.id+'/'+wyy+'/'+wmm+'/'+wdd,
			location.href = url2
		},
		*/
    });
});
</script>

