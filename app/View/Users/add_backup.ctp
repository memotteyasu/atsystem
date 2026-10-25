<div class="users form">
<?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend><?php echo __('Add User'); ?></legend>
        <?php echo $this->Form->input('username');
        echo $this->Form->input('password');
		echo $this->Form->input('kaisyas_id', array(
		  'type' => 'select',
		  'options' => $kaisyalist
		)
		);
		echo $this->Form->input('rolemasters_id', array(
		  'type' => 'select',
		  'options' => $rolemasterlist
		)
		);
    ?>
    </fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>

