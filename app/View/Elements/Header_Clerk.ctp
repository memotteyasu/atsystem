<body>
<!-- ヘッダー -->
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <button type="button" class="navbar-toggle pull-left animated flip">
                <span class="sr-only">navi</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?php echo
            	$this->Html->link(
            		$this->Html->image('logo20.png', array('alt' => 'asahitarns', 'class' => 'hidden-xs')).$user['Kaisya']['companyshortname'],
            		array(
            			'controller' => 'ClerkTops',
            			'action' => 'index'
            		),
            		array('escape' => false, 'class' => 'navbar-brand')
            	);
            ?>

            <ul class="collapse navbar-collapse nav navbar-nav top-menu">
                <li><?php echo $this->Html->link($user['usernameK'],array('controller'=>'ClerkTops','action'=>'index')); ?></li>
                <li><?php echo $this->Html->link('日報印刷（データなし）',array('controller'=>'DrTops', 'action'=>'pdf23', date('Y-m-d')), array('target'=>'_blank')); ?></li>
                <li><?php echo $this->Html->link('ログアウト',array('controller'=>'users', 'action'=>'logout')); ?></li>
            </ul>

        </div>
    </div>
    <!-- topbar ends -->
<!-- / ヘッダー -->
