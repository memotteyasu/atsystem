<div class="ch-container">
    <div class="row">
        <!-- left menu starts -->
		<?php echo $this->element('HostSidemenu'); ?>
        <!-- left menu ends -->
    <div id="content" class="col-lg-10 col-sm-10">
        <!-- content starts -->
			<ul class="breadcrumb">
				<li>
					<?php
						$this->Html->addCrumb('Top', ['controller' => 'HostTops', 'action' => 'index']);
						echo $this->element('breadcrumbs');
					?>
					<?php echo $this->Flash->render(); ?>
				</li>
			</ul>
		<style>
			.host-weekly-schedule-controls { margin-bottom: 8px; white-space: nowrap; }
			.host-weekly-schedule-controls select { display: inline-block; width: auto; min-width: 150px; vertical-align: middle; }
			.host-weekly-schedule-controls .previous-week-button { margin-right: 4px; vertical-align: middle; }
			.host-weekly-schedule-controls .next-week-button { margin-left: 4px; vertical-align: middle; }
			.host-weekly-schedule-controls .vehicle-schedule-button { margin-left: 4px; vertical-align: middle; }
			.host-schedule-management-link a { width: auto; padding: 2px 6px; }
			.host-weekly-schedule { width: 100%; min-width: 680px; font-size: 11px; margin-bottom: 0; table-layout: fixed; }
			.host-weekly-schedule th, .host-weekly-schedule td { padding: 4px 3px; text-align: center; vertical-align: middle; }
			.host-weekly-schedule .employee-name { width: 16%; font-size: 12px; text-align: left; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
			.host-weekly-schedule .schedule-cell-link { display: block; min-height: 17px; color: inherit; text-decoration: none; }
			.host-weekly-schedule .schedule-cell-link:hover { background-color: #f5f5f5; text-decoration: none; }
			.host-weekly-schedule .schedule-entry { display: block; margin: 1px 0; padding: 1px 2px; border: 2px solid; background-color: transparent; color: #333; font-weight: bold; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
			.host-weekly-schedule .company-schedule-row td { background-color: #e1f3ff; }
			.host-weekly-schedule .company-schedule-row .employee-name { color: #003c78; font-weight: bold; }
			.host-weekly-schedule .saturday { background: #f5f9ff; }
			.host-weekly-schedule .sunday { background: #fff6f6; }
		</style>
		<div class="row">
			<div class="box col-md-12">
				<div class="box-inner">
					<div class="box-header well">
						<h2><i class="glyphicon glyphicon-calendar"></i> 事務員スケジュール（<?php echo h($weekStart->format('n/j')); ?>〜<?php echo h($weekEnd->format('n/j')); ?>）</h2>
						<div class="box-icon host-schedule-management-link"><a href="<?php echo $this->Html->url(array('controller' => 'HostSchedules', 'action' => 'index')); ?>" class="btn btn-xs btn-default">予定管理</a></div>
					</div>
					<div class="box-content">
						<form action="<?php echo $this->Html->url(array('controller' => 'HostTops', 'action' => 'index')); ?>" method="get" class="host-weekly-schedule-controls">
							<label for="week-offset">表示期間&nbsp;</label>
							<?php if ((int)$weekOffset > -11): ?>
								<?php echo $this->Html->link('前週', array('controller' => 'HostTops', 'action' => 'index', '?' => array('week_offset' => (int)$weekOffset - 1)), array('class' => 'btn btn-warning btn-sm previous-week-button')); ?>
							<?php else: ?>
								<button type="button" class="btn btn-warning btn-sm previous-week-button" disabled>前週</button>
							<?php endif; ?>
							<select name="week_offset" id="week-offset" class="form-control input-sm" onchange="this.form.submit()">
								<?php foreach ($weekOptions as $value => $label): ?>
									<option value="<?php echo h($value); ?>"<?php echo (int)$value === (int)$weekOffset ? ' selected' : ''; ?>><?php echo h($label); ?></option>
								<?php endforeach; ?>
							</select>
							<?php if ((int)$weekOffset < 11): ?>
								<?php echo $this->Html->link('次週', array('controller' => 'HostTops', 'action' => 'index', '?' => array('week_offset' => (int)$weekOffset + 1)), array('class' => 'btn btn-warning btn-sm next-week-button')); ?>
							<?php else: ?>
								<button type="button" class="btn btn-warning btn-sm next-week-button" disabled>次週</button>
							<?php endif; ?>
							<?php echo $this->Html->link('車両予定', array('controller' => 'VehicleSchedules', 'action' => 'index'), array('class' => 'btn btn-default btn-sm vehicle-schedule-button')); ?>
						</form>
						<div class="table-responsive">
							<table class="table table-bordered table-condensed host-weekly-schedule">
								<thead>
									<tr>
										<th class="employee-name">従業員</th>
										<?php foreach ($weekDates as $weekDate): ?>
											<th class="<?php echo $weekDate['weekday'] === 6 ? 'saturday' : ($weekDate['weekday'] === 7 ? 'sunday' : ''); ?>"><?php echo h($weekDate['label']); ?></th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<tr class="company-schedule-row">
										<td class="employee-name">社内全体</td>
										<?php foreach ($weekDates as $weekDate): ?>
											<?php $calendarEntries = isset($companyCalendarEntriesByDate[$weekDate['date']]) ? $companyCalendarEntriesByDate[$weekDate['date']] : array(); ?>
											<?php $scheduleAddUrl = $this->Html->url(array('controller' => 'HostSchedules', 'action' => 'add', '?' => array('company_schedule' => 1, 'scheduledate' => $weekDate['date']))); ?>
											<td class="<?php echo empty($calendarEntries) ? ($weekDate['weekday'] === 6 ? 'saturday' : ($weekDate['weekday'] === 7 ? 'sunday' : '')) : ''; ?>">
												<a href="<?php echo $scheduleAddUrl; ?>" class="schedule-cell-link" title="社内全体の予定を登録">
													<?php foreach ($calendarEntries as $calendarEntry): ?>
														<span class="schedule-entry" style="border-color:<?php echo h($calendarEntry['color']); ?>;" title="<?php echo h($calendarEntry['name']); ?>"><?php echo h($calendarEntry['name']); ?></span>
													<?php endforeach; ?>
												</a>
											</td>
										<?php endforeach; ?>
									</tr>
									<?php foreach ($scheduleUsers as $scheduleUser): ?>
										<tr>
											<td class="employee-name" title="<?php echo h($scheduleUser['User']['usernameK']); ?>"><?php echo h($scheduleUser['User']['usernameK']); ?></td>
											<?php foreach ($weekDates as $weekDate): ?>
												<?php $calendarEntries = isset($calendarEntriesByUserAndDate[$scheduleUser['User']['id']][$weekDate['date']]) ? $calendarEntriesByUserAndDate[$scheduleUser['User']['id']][$weekDate['date']] : array(); ?>
												<?php $scheduleAddUrl = $this->Html->url(array('controller' => 'HostSchedules', 'action' => 'add', '?' => array('users_id' => $scheduleUser['User']['id'], 'scheduledate' => $weekDate['date']))); ?>
												<td class="<?php echo empty($calendarEntries) ? ($weekDate['weekday'] === 6 ? 'saturday' : ($weekDate['weekday'] === 7 ? 'sunday' : '')) : ''; ?>">
													<a href="<?php echo $scheduleAddUrl; ?>" class="schedule-cell-link" title="予定を登録">
													<?php foreach ($calendarEntries as $calendarEntry): ?>
														<span class="schedule-entry" style="border-color:<?php echo h($calendarEntry['color']); ?>;" title="<?php echo h($calendarEntry['name']); ?>"><?php echo h($calendarEntry['name']); ?></span>
													<?php endforeach; ?>
													</a>
												</td>
											<?php endforeach; ?>
										</tr>
									<?php endforeach; ?>
									<?php if (empty($scheduleUsers)): ?>
										<tr><td colspan="8">表示できる在籍従業員がいません。</td></tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-warning-sign"></i> 注意事項</h2>

		            </div>
		            <div class="box-content row">
		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php
								if(!$calendarmasters){ /* ２ヶ月先のデータがなければ警告を表示 */
									switch ($user['Rolemaster']['id']){
										case '10':
										case '11':
										case '12':
										case '21':
										case '22':
										case '23':
										case '24':
										case '25':
											echo '<li class="text-danger">２ヶ月先のカレンダーが登録されていません。確認お願いします。</li>';
										default:
											break;
									}
								 ?>
								<?php } ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> <?php echo $this->Html->link('来社予定登録',[
		                	'controller'=>'Re010s',
		                ]); ?></h2>

		            </div>
		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($receptionmessages as $receptionmessage): ?>
									<li>
										<?php
											$date = new DateTime($receptionmessage['Receptionmessage']['receptiondate']);
											echo $date->format(h('Y年m月d日 H時i分'));
										?>
										<?php echo '　'; ?>
										<?php echo $receptionmessage['Receptionmessage']['receptioncomment']; ?>(<?php echo $receptionmessage['Receptionmessage']['personalname']; ?>) <?php //echo $this->Form->postLink('削除',['action'=>'deletereception',$receptionmessage['Receptionmessage']['id']],['class'=>'label label-danger']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>

		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 個人メモ</h2>

		            </div>
		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($personalmessages as $personalmessage): ?>
									<li>
										<?php //echo date('Y-m-d H:i', strtotime($personalmessage['Personalmessage']['created'])); ?>
										<?php
											$date = new DateTime($personalmessage['Personalmessage']['created']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo '　'; ?>
										<?php echo $personalmessage['Personalmessage']['personalcomment']; ?> <?php echo $this->Form->postLink('削除',['action'=>'deletepersonal',$personalmessage['Personalmessage']['id']],['class'=>'label label-danger']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well" data-original-title="">
		                <h2><i class="glyphicon glyphicon-edit"></i> 個人メモ登録</h2>

		            </div>
		            <div class="box-content">

		                <div class="personalmessages form">
							<?php echo $this->Form->create('Personalmessage',[
								'url'=>[
									'controller'=>'HostTops',
									'action'=>'addpersonalmessage'
								],
								'label'=>false,
								'div'=>false,
								'class'=>'form-horizontal',
								'novalidate'=>true
							]); ?>
								<fieldset>
									<?php
										echo $this->Form->input('personalcomment',[
											'label'=>false,
											'div'=>false,
											'type'=>'text',
											'class'=>'form-control',
											'placeholder'=>'メモを入力',
											'style'=>'width:95%;margin-bottom:5px;',
										]);
									?>
								</fieldset>
								<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-default', 'name' => 'submit']); ?>
							<?php echo $this->Form->end(); ?>
						</div>

		            </div>
		        </div>
		    </div>
		    <!--/span-->
		</div><!--/row-->


		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 業務メッセージ</h2>

		            </div>
		            <div class="box-content row">

						<div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($gyoumumessages as $gyoumumessage): ?>
									<li>
										<?php //echo date('Y-m-d H:i', strtotime(h($gyoumumessage['Gyoumumessage']['created']))); ?>
										<?php
											$date = new DateTime($gyoumumessage['Gyoumumessage']['created']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo '　'; ?>
										<?php echo $gyoumumessage['Gyoumumessage']['gyoumucomment']; ?> <?php echo $this->Form->postLink('削除',['action'=>'deletegyoumu',$gyoumumessage['Gyoumumessage']['id']],['class'=>'label label-danger']); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>

		            </div>
		        </div>
		    </div>
		</div>
		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well" data-original-title="">
		                <h2><i class="glyphicon glyphicon-edit"></i> 業務メッセージ登録</h2>

		            </div>
		            <div class="box-content">

		            	<div class="gyoumumessages form">
							<?php
								echo $this->Form->create('Gyoumumessage',[
									'url'=>[
										'controller'=>'HostTops',
										'action'=>'addgyoumumessage'
								],
									'label'=>false,
									'div'=>false,
									'class'=>'form-horizontal',
									'novalidate'=>true
								]);
							?>
								<fieldset>
									<?php
										echo $this->Form->input('gyoumucomment',[
											'label'=>false,
											'div'=>false,
											'type'=>'text',
											'class'=>'form-control',
											'placeholder'=>'メッセージを入力',
											'style'=>'width:95%;margin-bottom:5px;',
										]);
									?>
								</fieldset>
								<?php echo $this->Form->submit('登録', ['div'=>false, 'class' => 'btn btn-default', 'name' => 'submit']); ?>
							<?php echo $this->Form->end(); ?>
						</div>

		            </div>
		        </div>
		    </div>
		    <!--/span-->

		</div><!--/row-->

		<div class="row">
		    <div class="box col-md-12">
		        <div class="box-inner">
		            <div class="box-header well">
		                <h2><i class="glyphicon glyphicon-info-sign"></i> 有休等申請</h2>

		            </div>

		            <div class="box-content row">

		                <div class="col-lg-12 col-md-12">
							<ul>
								<?php foreach($employeeholidays as $employeeholiday): ?>
									<?php
										$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
										$today = new DateTime();
										if ($date->format('Y-m-d') >= $today->format('Y-m-d')):
									?>
										<li>
											<?php echo $date->format(h('Y年m月d日')); ?>
											<?php echo ' '; ?>
											<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?>　<?php echo($employeeholiday['Flgmaster2']['flgname']) ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
										</li>
									<?php else: ?>
										<?php if ($employeeholiday['Flgmaster']['id'] == 42): ?>
											<li>
												<?php echo $date->format(h('Y年m月d日')); ?>
												<?php echo ' '; ?>
												<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?>　<?php echo($employeeholiday['Flgmaster2']['flgname']) ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
											</li>
										<?php endif; ?>
									<?php endif; ?>

									<!--
									<li>
										<?php
											$date = new DateTime($employeeholiday['Employeeholiday']['applicationdate']);
											echo $date->format(h('Y年m月d日'));
										?>
										<?php echo ' '; ?>
										<?php echo $this->Html->link(h($employeeholiday['User']['usernameK']),['controller'=>'Host012s','action'=>'index']); ?> (<?php echo h($employeeholiday['Flgmaster']['flgname']); ?>)
									</li>
									-->
								<?php endforeach; ?>
							</ul>
						</div>
		            </div>
		        </div>
		    </div>
		</div>
    <!-- content ends -->
    </div><!--/#content.col-md-0-->
</div><!--/fluid-row-->
    <hr>

</div><!--/.fluid-container-->
<script type="text/javascript">
jQuery(function(){
	jQuery.datepicker.setDefaults(jQuery.datepicker.regional[ "ja" ] );
	var dateFormat = 'yy/mm/dd';
	jQuery('.datepicker').datepicker({
		dateFormat: 'yy/mm/dd(DD)'
	});

	jQuery(document).ready(function(){
			jQuery.datetimepicker.setLocale('ja');
			jQuery('input.datetimepicker1').datetimepicker({
				format:'Y-m-d H:i',
				step:30,
			});
	});
});
</script>


