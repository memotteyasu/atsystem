<?php
	echo $this->Form->create($model, ['url' => $url, 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

		echo $text;
		echo '　　';

		echo $this->Form->input($input, [
			'label' => false,
			'type' => 'select',
			'div' => false,
			'class' => 'form-control',
			'empty'=>'未選択',
			'onchange' => 'this.form.submit()',
			'data-rel' => 'chosen',
		]);

		echo '　　';
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
