<?php
	echo $this->Form->create($model, ['url' => $url, 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

		echo '　　'.$text3.'　';

		echo $this->Form->input($input3, [
			'label' => false,
			'type' => 'text',
			'div' => false,
			'class' => 'form-control',
			'placeholder'=>$text3.'を入力',
			'onchange' => 'this.form.submit()',
			//'data-rel' => 'chosen',
		]);

	echo $this->Form->end()
?>
