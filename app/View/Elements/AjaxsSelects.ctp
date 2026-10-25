<?php
	$this->Js->get($selectid1)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action, $model],
			[
				'update' => $selectid2,
				'dataExpression' => true,
				'data' => '$("'.$selectid1.'").serialize()',
				'complete' => '$("'.$selectid2.'").change();',
			]
		)
	);

	$this->Js->get($selectid2)->event(
		'change',
		$this->Js->request(
			['controller' => 'AjaxsSelects', 'action' => $action2, $model],
			[
				'update' => $selectid3,
				'dataExpression' => true,
				'data' => '$("'.$selectid2.'").serialize()'
			]
		)
	);

	echo $this->Js->writeBuffer();
?>
