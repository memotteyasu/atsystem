<?php foreach($receptionmessages as $receptionmessage){}; ?>
<?php if(isset($receptionmessage)){ ?>
<div class="ch-container">
    <div class="row">

<div class="row">
    <div class="box col-md-12">
        <div class="box">
            <div class="text-center">
                <h1 style="margin-top:110px;font-size:3em">来社予定のお客様</h1>
				<h1 style="font-size:4em;">
				<?php
					$date = new DateTime($receptionmessage['receptiondate']);
					echo $date->format(h('m月d日　H時i分'));
				?>
                </h1>
            </div>
<?php
// ３件とも会社名が入っている場合
if(($receptionmessage['receptioncomment'])and($receptionmessage['receptioncomment2'])and($receptionmessage['receptioncomment3'])){
?>
            <div class="row">
                <div class="col-lg-4">
                    <div class="box-content" style="margin-top:30px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3.5em;">
                        <?php echo h($receptionmessage['receptioncomment']); ?>
                        </h1>
                        </div>
                    </div>
                    <div class="box-content row" style="margin-top:0px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3em;">
                        <?php echo h($receptionmessage['personalname']); ?>
                        </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box-content" style="margin-top:30px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3.5em;">
                        <?php echo h($receptionmessage['receptioncomment2']); ?>
                        </h1>
                        </div>
                    </div>
                    <div class="box-content" style="margin-top:0px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3em;">
                        <?php echo h($receptionmessage['personalname2']); ?>
                        </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="box-content" style="margin-top:30px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3.5em;">
                        <?php echo h($receptionmessage['receptioncomment3']); ?>
                        </h1>
                        </div>
                    </div>
                    <div class="box-content" style="margin-top:0px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:3em;">
                        <?php echo h($receptionmessage['personalname3']); ?>
                        </h1>
                        </div>
                    </div>
                </div>
            </div>
<?php
}elseif(($receptionmessage['receptioncomment'])and($receptionmessage['receptioncomment2'])){
?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="box-content" style="margin-top:70px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:5em;">
                        <?php echo h($receptionmessage['receptioncomment']); ?>
                        </h1>
                        </div>
                    </div>
                    <div class="box-content" style="margin-top:0px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:4em;">
                        <?php echo h($receptionmessage['personalname']); ?>
                        </h1>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="box-content" style="margin-top:50px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:5em;">
                        <?php echo h($receptionmessage['receptioncomment2']); ?>
                        </h1>
                        </div>
                    </div>
                    <div class="box-content" style="margin-top:0px;">
                        <div class="col-lg-12 col-md-12 text-center">
                        <h1 style="font-size:4em;">
                        <?php echo h($receptionmessage['personalname2']); ?>
                        </h1>
                        </div>
                    </div>
                </div>
            </div>

<?php
}elseif($receptionmessage['receptioncomment']){
?>
            <div class="box-content row" style="margin-top:100px;margin-bottom:50px;">
                <div class="col-lg-12 col-md-12 text-center">
				<h1 style="font-size:6em;">
				<?php echo h($receptionmessage['receptioncomment']); ?>
				</h1>
				</div>
            </div>
            <div class="box-content row" style="margin-top:10px;margin-bottom:70px;">
                <div class="col-lg-12 col-md-12 text-center">
				<h1 style="font-size:5em;">
				<?php echo h($receptionmessage['personalname']); ?>
				</h1>
				</div>
            </div>
<?php
}
 ?>
            <div class="box-content row">
                <div class="col-lg-12 col-md-12 text-center">
				<h1 style="font-size:3.5em;margin-top:20px;">
                ようこそ　いらっしゃいませ
				</h1>
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
<?php }else{ ?>
<div class="ch-container">
    <div class="row">

<div class="row">
    <div class="box col-md-12">
        <div class="box">
            <div class="text-center">
                <h1 style="margin-top:200px;font-size:4em;">ご来社のお客様</h1>
            </div>

            <div class="box-content row">
                <div class="col-lg-12 col-md-12 text-center">
				<h1 style="font-size:5em;margin-top:160px;">
                ようこそ　いらっしゃいませ
				</h1>
				<h1 style="font-size:5em;margin-top:50px;">
                直接、右のドアよりお入りください。
				</h1>
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
<?php } ?>