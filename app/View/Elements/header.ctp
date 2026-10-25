<body>
<!-- ヘッダー -->
    <!-- topbar starts -->
    <div class="navbar navbar-default" role="navigation">

        <div class="navbar-inner">
            <?php echo
            	$this->Html->link(
            		$this->Html->image('logo20.png', array('alt' => 'asahitarns', 'class' => 'hidden-xs')).'ATsystem',
            		array(
            			'controller' => 'Users',
            			'action' => 'login'
            		),
            		array('escape' => false, 'class' => 'navbar-brand')
            	);
            ?>

            <ul class="collapse navbar-collapse nav navbar-nav top-menu">
                <li></li>
            </ul>

        </div>
    </div>
    <!-- topbar ends -->

<!-- / ヘッダー -->
