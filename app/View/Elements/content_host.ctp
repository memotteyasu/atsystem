
<!-- 明細表示 -->
<?php
// controllerがHostConfirmsなら処理しない

if(($this->name == 'HostConfirms')or($this->name == 'HostStatisticas')){
}else{
?>
<section id="wrapper">
<?php 
foreach ($gethost as $gethosts){
?>
<div class="col4"></div>
<div class="gridw">
<?php echo $gethosts['Oilordertran']['startdate']; ?><?php echo ' ('.__(date('D',strtotime($gethosts['Oilordertran']['startdate']))); ?>)  〜  <?php echo $gethosts['Oilordertran']['enddate']; ?><?php echo ' (',__(date('D',strtotime($gethosts['Oilordertran']['enddate']))); ?>)
</div>
<div class="gridw gridw-color">
■■　<?php echo $gethosts['Tradings']['companyshortname']; ?>
</div>
<div class="gridw">
<?php echo $gethosts['Loadings']['companyshortname']; ?>ー＞<?php echo $gethosts['Destinations']['companyshortname']; ?> (<?php echo $gethosts['Gyokuareas']['companyshortname']; ?>)
</div>
<div class="gridw">
<?php echo $gethosts['Oilmaster']['oilname']; ?> (残数)　<?php 
$zan = $gethosts['Oilordertran']['orderamount'] - $gethosts['Oilordertran']['oilzan'];
echo $zan;
?> 
</div>
<div id="mainBanner"></div>

<?php
}
?>


</section>
<?php
}
?>
