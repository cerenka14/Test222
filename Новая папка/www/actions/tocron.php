<?php
include('../inc/conf.php');
include('cpayeer.php');

$dt = time();
$accountNumber = $p_out_number;
$apiId = $p_out_id;
$apiKey = $p_out_key;
$payeer = new CPayeer($accountNumber, $apiId, $apiKey);

//Смотрим баланс рыб
$fish_1_sum = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT SUM(`sum`) AS `s` FROM t_play WHERE fish = '1' AND st = '1'"));
$fish_2_sum = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT SUM(`sum`) AS `s` FROM t_play WHERE fish = '2' AND st = '1'"));
$fish_1_sum_e = $fish_1_sum['s'];
$fish_2_sum_e = $fish_2_sum['s'];

//По нулям
$summed = $fish_1_sum_e + $fish_2_sum_e;
if ($summed == 0) { mysqli_query($connect_db, "INSERT INTO `t_games` (f,dt) VALUES ('3','$dt')"); mysqli_query($connect_db, "DELETE FROM `t_play`"); exit; }

//Ничья, возврат средств
if ($fish_1_sum_e == $fish_2_sum_e || $fish_1_sum_e == 0 || $fish_2_sum_e == 0) {

$qin = mysqli_query($connect_db, "SELECT id,payeer,sum FROM t_play WHERE st = '1'");
while($rowi = mysqli_fetch_array($qin)) {

if ($payeer->isAuth())
{
$arTransfer = $payeer->transfer(array(
'curIn' => 'RUB',
'sum' => $rowi['sum'],
'curOut' => 'RUB',
'to' => $rowi['payeer'],
'anonim' => 'Y',
'comment' => iconv('windows-1251', 'utf-8', 'Cashback from '.SITE)
));
if (empty($arTransfer['errors']))
{
} else { } } else {  }

}
mysqli_query($connect_db, "INSERT INTO `t_games` (f,dt) VALUES ('3','$dt')"); mysqli_query($connect_db, "DELETE FROM `t_play`"); exit;
}

//Комиссия админу и реферерам
$fish_1_sum_e = $fish_1_sum_e-($fish_1_sum_e*($toadmin+$toref));
$fish_2_sum_e = $fish_2_sum_e-($fish_2_sum_e*($toadmin+$toref));

//Определение победившей рыбы
if ($fish_1_sum_e > $fish_2_sum_e) { $fish_win = 1; $fish_lose = 2; $summed_win_nop = $fish_1_sum['s']; $summed_loose = $fish_2_sum_e; }
if ($fish_1_sum_e < $fish_2_sum_e) { $fish_win = 2; $fish_lose = 1; $summed_win_nop = $fish_2_sum['s']; $summed_loose = $fish_1_sum_e; }

//Зачисление средств
$qin = mysqli_query($connect_db, "SELECT id,payeer,ref,sum FROM t_play WHERE fish = '$fish_win' AND st = '1'");
while($rowi = mysqli_fetch_array($qin)) {

//Проверка реферера
$myref = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT id,usr FROM t_ref WHERE ref = '$rowi[payeer]'"));

//Рассчёт выигрыша
$one_percent = $summed_win_nop/100;
$my_percent = ($rowi['sum']/$one_percent)/100;
$my_win = $rowi['sum'] + ($summed_loose*$my_percent);

if (!empty($myref['usr'])) {
$torefsum = $my_win*$toref;
if ($payeer->isAuth())
{
$arTransfer = $payeer->transfer(array(
'curIn' => 'RUB',
'sum' => $torefsum,
'curOut' => 'RUB',
'to' => $myref['usr'],
'anonim' => 'Y',
'comment' => iconv('windows-1251', 'utf-8', 'By referall on '.SITE)
));
if (empty($arTransfer['errors']))
{
mysqli_query($connect_db, "UPDATE `t_ref` SET `prf` = `prf`+$torefsum WHERE usr = '$myref[usr]' AND ref = '$rowi[payeer]'");
} else {
echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
} } else {
echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
}

}

if ($payeer->isAuth())
{
$arTransfer = $payeer->transfer(array(
'curIn' => 'RUB',
'sum' => $my_win,
'curOut' => 'RUB',
'to' => $rowi['payeer'],
'anonim' => 'Y',
'comment' => iconv('windows-1251', 'utf-8', 'Victory on '.SITE)
));
if (empty($arTransfer['errors']))
{
} else {
echo '<pre>'.print_r($arTransfer["errors"], true).'</pre>';
} } else {
echo '<pre>'.print_r($payeer->getErrors(), true).'</pre>';
}

}

mysqli_query($connect_db, "INSERT INTO `t_games` (f,s1,s2,dt) VALUES ('$fish_win','$fish_1_sum[s]','$fish_2_sum[s]','$dt')");
mysqli_query($connect_db, "DELETE FROM `t_play`");
?>