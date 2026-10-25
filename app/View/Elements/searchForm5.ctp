<?php
	echo $this->Form->create($model, ['url' => $url, 'label' => false, 'div' => 'form-group', 'class' => 'form-inline', 'novalidate' => true]);

		echo '　　'.$text3;

		echo $this->Form->input($input3, [
			'label' => false,
			'type' => 'text',
			'div' => false,
			'class' => 'form-control',
			'placeholder'=>$text3.'を入力',
			//'onchange' => 'this.form.submit()',
			//'data-rel' => 'chosen',
		]);

		echo '　　';
		echo $text4;

		echo $this->Form->input($input4, [
			'label' => false,
			'type' => 'text',
			'div' => false,
			'class' => 'form-control',
			'placeholder'=>$text4.'を入力',
			//'onchange' => 'this.form.submit()',
		]);
	echo $this->Form->submit('検索', ['div'=>false, 'class' => 'btn btn-info', 'name' => 'submit']);
	echo $this->Form->end()
?>
