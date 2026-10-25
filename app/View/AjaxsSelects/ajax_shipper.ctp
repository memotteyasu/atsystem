<option value="0"><?php echo '選択してください'; ?></option>
<?php foreach($shippermasters as $key => $val) : ?>
<option value="<?php echo $key; ?>"><?php echo $val; ?></option>
<?php endforeach; ?>