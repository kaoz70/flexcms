<? if(count($backgrounds) > 1):?>

<?
	$seconds = 8;
	$final_time = (count($backgrounds) * $seconds);
?>

<? foreach ($backgrounds as $key => $image): ?>
.cb-slideshow li:nth-child(<?=$key + 1?>) {
	-webkit-animation-delay: <?=$key * $seconds ?>s;
	-moz-animation-delay: <?=$key * $seconds ?>s;
	-ms-animation-delay: <?=$key * $seconds ?>s;
	-o-animation-delay: <?=$key * $seconds ?>s;
	animation-delay: <?=$key * $seconds ?>s;
}
<? endforeach ?>

.cb-slideshow li {
	-webkit-animation: backgroundAnimation <?= $final_time ?>s linear infinite 0s;
	-moz-animation: backgroundAnimation <?= $final_time ?>s linear infinite 0s;
	-ms-animation: backgroundAnimation <?= $final_time ?>s linear infinite 0s;
	-o-animation: backgroundAnimation <?= $final_time ?>s linear infinite 0s;
	animation: backgroundAnimation <?= $final_time ?>s linear infinite 0s;
}

<? elseif (count($backgrounds) === 1): ?>
body {background-image: url("<?= base_url('themes/' . $theme . '/images/fondos/' . $backgrounds[0]) ?>") !important;}
<? endif ?>