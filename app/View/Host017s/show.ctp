<!-- 年間拘束時間 -->
<!-- Host017s -->
<style>
	table.table-clickable td[data-href]{
		cursor: pointer;
	}
	table.table-clickable td:hover{
		background-color:#e8e8e8 !important;
	}
</style>

<div class="ch-container">
	<div class="row">
    <!-- left menu starts -->
		<?php echo $this->element('Sidemenu'); ?>
    <!-- left menu ends -->


    <div id="content" class="col-lg-10 col-md-10 col-sm-10">
      <!-- content starts -->
      <div>
        <ul class="breadcrumb">
          <li>
            <?php
              $this->Html->addCrumb('Top', ['controller' => $role.'Tops', 'action' => 'index']);
              echo $this->element('breadcrumbs');
            ?>
            <?php echo $this->Flash->render(); ?>
          </li>
        </ul>
      </div>

      <!-- table starts -->
      <div class="row">
        <div class="box col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <div class="box-inner">
            <div class="box-header well">
              <h2><i class="glyphicon glyphicon-info-sign"></i> <?= $data['User']['usernameK'] ?></h2>
            </div>
            <div class="box-content">
              <p>
                月範囲を指定して拘束時間の印刷を行う。
                <?//php
                  //echo $this->Form->create(false, ['label' => false, 'div' => 'form-group', 'class' => 'form-inline']);

                    // echo $this->Form->input('selectym1', [
                    //   'div' => false,
                    //   'label' => false,
                    //   'type' => 'select',
                    //   'class'=>'form-control',
                    //   'onchange'=>'this.form.submit()',
                    //   'options'=>$yearlist,
                    //   'default' => $w_y
                    // ]);

                    // echo '　年　';

                    // echo $this->Form->input('selectym1', [
                    //   'div'=>false,
                    //   'label'=>false,
                    //   'type' => 'date',
                    //   'dateFormat' => 'YM',
                    //   'monthNames' => false,
                    //   'minYear' => date('Y')-2,
                    //   'maxYear' => date('Y')+1,
                    //   'onchange'=>'this.form.submit()',
                    //   'data-rel'=>'chosen',
                    //   'style'=>'width:70px',
                    //   'default' => $paramsDate1
                    // ]);

                    // echo '　から　';

                    // echo $this->Form->input('selectym2', [
                    //   'div'=>false,
                    //   'label'=>false,
                    //   'type' => 'date',
                    //   'dateFormat' => 'YM',
                    //   'monthNames' => false,
                    //   'minYear' => date('Y')-2,
                    //   'maxYear' => date('Y')+1,
                    //   'onchange'=>'this.form.submit()',
                    //   'data-rel'=>'chosen',
                    //   'style'=>'width:70px',
                    //   'default' => $paramsDate2
                    // ]);

                    //echo '　のデータを印刷する。';

                  //echo $this->Form->end();
                //?>

                <?php
                  echo $this->Form->create(null, ['type' => 'get', 'label' => false, 'div' => 'form-group', 'class' => 'form-inline']); // フォーム開始

                  // 開始年月
                  echo $this->Form->control('selectym1', [
                      'type' => 'month',
                      'label' => '開始年月',
                      'min' => $minDate,       // Controllerで生成した最小値
                      'max' => $startMax,      // Controllerで生成した終了年月の選択値まで
                      'value' => $selectedStart,
                      'id' => 'selectym1',
                      'div' => false,
                      'class' => 'form-control',
                      'onchange' => 'this.form.submit();', // 自動送信
                  ]);

                  echo '　';

                  // 終了年月
                  echo $this->Form->control('selectym2', [
                      'type' => 'month',
                      'label' => '終了年月',
                      'min' => $endMin,        // Controllerで生成した開始年月の選択値から
                      'max' => $maxDate,       // Controllerで生成した最大値
                      'value' => $selectedEnd,
                      'id' => 'selectym2',
                      'div' => false,
                      'class' => 'form-control',
                      'onchange' => 'this.form.submit();', // 自動送信
                  ]);

                  echo $this->Form->end(); // フォーム終了
                ?>

                <br>

                <?= $this->Html->link(('個人月間拘束印刷'), [
                    'action' => 'pdfhost002_5y2',
                    $data['User']['id'],
                    $selectedStart,
                    $selectedEnd
                  ], [
                    'class' => ['btn btn-success'],
                    'target'=>'_blank'
                  ]);
                ?>
              </p>
            </div>
          </div>
        </div>
      </div>
      <!-- table ends -->

      <p>

      </p>
    <!-- content ends -->
    </div>
	</div><!--/fluid-row-->

</div><!--/.fluid-container-->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const startInput = document.getElementById('selectym1'); // 開始年月
    const endInput = document.getElementById('selectym2');   // 終了年月

    function updateEndMinMax() {
        if (startInput.value) {
            const startDate = new Date(startInput.value + '-01');
            const maxEndDate = new Date(startDate);
            maxEndDate.setMonth(maxEndDate.getMonth() + 12); // 最大1年後

            const minEndDate = startInput.value;
            const maxEndDateString = maxEndDate.toISOString().slice(0, 7);

            endInput.min = minEndDate;
            endInput.max = maxEndDateString;

            if (endInput.value < minEndDate || endInput.value > maxEndDateString) {
                endInput.value = maxEndDateString; // 範囲外の場合はデフォルト値をセット
            }
        }
    }

    startInput.addEventListener('change', updateEndMinMax);
    updateEndMinMax();
  });

</script>
