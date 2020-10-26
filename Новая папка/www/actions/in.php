<?php
include('../inc/conf.php');
ini_set('session.use_cookies', 'On');
ini_set('session.use_trans_sid', 'Off');
session_set_cookie_params(604800000, "/", SITE, false, false);
session_start();

if(isset($_POST['sum'])){
if(!empty($_POST['sum'])){
    if ($_POST['sum'] <= 0) { exit('Сумма ставки меньше или равна нулю'); }
	$fish = intval($_POST['fish']);
    if ($fish < 1 || $fish > 2) { exit('Ошибка'); }
	if ($fish == 1) { $fish_t = 'красную'; }
	if ($fish == 2) { $fish_t = 'зелёную'; }

$dt = time();
$sum = preg_replace("#[^0-9\.]+#i",'',mysqli_real_escape_string($connect_db, trim($_POST['sum'])));

$ref = mysqli_real_escape_string($connect_db, preg_replace("#[^P/p/0-9]+#i",'', base64_decode($_COOKIE['referer'])));
mysqli_query($connect_db, "INSERT INTO `t_play` (ref,fish,dt) VALUES ('$ref','$fish','$dt')");
$batch = mysqli_insert_id($connect_db);

$m_shop = $p_shop_id;
$m_key = $p_key;
$m_orderid = $batch;
$m_amount = number_format($sum, 2, '.', '');
$m_curr = 'RUB';
$m_desc = base64_encode("Ставка на $fish_t рыбу");
$arHash = array(
$m_shop,
$m_orderid,
$m_amount,
$m_curr,
$m_desc,
$m_key
);
$sign = strtoupper(hash('sha256', implode(':', $arHash)));

echo '
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Переход к оплате</title>
<style>
.holder { position: absolute; top: 50%; left: 50%; } .global { height: 200px; width: 300px; margin-top:-100px; margin-left:-150px; } .gear { top: 0 !important; height: 100px; width: 100px; margin-left:-50px; } .label { font-family:Arial,Helvetica,Sans-serif; font-size:18px; width: 170px; top: 60% !important; margin-left:-75px; }
</style>
</head>
<body>
<div class="holder global">
<div class="holder gear">
<img src="/img/load.gif">
</div>
<span class="holder label">Переход к оплате...</span>
</div>
<form id="send" method="GET" action="https://payeer.com/merchant/" style="display:none">
<input type="hidden" name="m_shop" value="'.$m_shop.'">
<input type="hidden" name="m_orderid" value="'.$m_orderid.'">
<input type="hidden" name="m_amount" value="'.$m_amount.'">
<input type="hidden" name="m_curr" value="'.$m_curr.'">
<input type="hidden" name="m_desc" value="'.$m_desc.'">
<input type="hidden" name="m_sign" value="'.$sign.'">
<input type="submit" name="m_process" value="send" />
</form>
<script type="text/javascript">
document.forms["send"].submit();
</script>
</body>
</html>
';
} else { echo 'Вы не ввели сумму ставки'; }
} else { echo 'Ошибка'; }
?>