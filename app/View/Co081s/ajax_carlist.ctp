<option value="">未選択</option>
<?php foreach ($carlist as $key => $val) : ?>
<option value="<?php echo h($key); ?>"><?php echo h($val); ?></option>
<?php endforeach; ?>
