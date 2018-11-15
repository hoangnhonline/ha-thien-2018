<?php 
exec('casperjs --load-images=false test.js', $arr, $res); 
$gia = str_replace(" ZEL/BTC Exchange / Stocks.exchange", "", $arr[0]);
echo $gia;
?>
<title><?php echo $gia; ?></title>
<script type="text/javascript">
	setInterval(function(){ window.location.reload(); }, 1000*20);
</script>