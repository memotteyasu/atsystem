<?php
	echo $this->Paginator->counter(['format' => ('{:count}件中{:start}-{:end}件（{:page}ページ中{:pages}ページ）')]);
?>

<div class="text-center">
	<ul class="pagination">
		<?php
			echo $this->Paginator->first('最初');
			echo $this->Paginator->prev(('前へ'),['tag' => 'li'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a']);
			echo $this->Paginator->numbers(['separator' => '', 'currentTag' => 'a', 'currentClass' => 'active', 'tag' => 'li']);
			echo $this->Paginator->next(('次へ'), ['tag' => 'li', 'currentClass' => 'disabled'], null, ['tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a']);
			echo $this->Paginator->last('最後');
		?>
	</ul>
</div>
