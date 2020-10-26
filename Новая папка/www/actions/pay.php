<?php
include('../inc/conf.php');
//if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189'))) return;
if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
{
$m_key = $p_key;
$arHash = array($_POST['m_operation_id'],
$_POST['m_operation_ps'],
$_POST['m_operation_date'],
$_POST['m_operation_pay_date'],
$_POST['m_shop'],
$_POST['m_orderid'],
$_POST['m_amount'],
$_POST['m_curr'],
$_POST['m_desc'],
$_POST['m_status'],
$m_key);
$sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));
if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
{

$payeer = mysqli_real_escape_string($connect_db, $_POST['client_account']);
$sum = mysqli_real_escape_string($connect_db, $_POST['m_amount']);
$batch = mysqli_real_escape_string($connect_db, $_POST['m_orderid']);
$dt = time();

$toref = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT id,ref FROM t_play WHERE id = '$batch'"));
if ($payeer != $toref['ref']) {
$how = mysqli_num_rows(mysqli_query($connect_db, "SELECT id FROM t_ref WHERE usr = '$toref[ref]' AND ref = '$payeer'"));
if ($how == 0) { mysqli_query($connect_db, "INSERT INTO `t_ref` (usr,ref,dt) VALUES ('$toref[ref]','$payeer','$dt')"); }
}

mysqli_query($connect_db, "UPDATE `t_play` SET `payeer` = '$payeer', `sum` = '$sum', `st` = '1' WHERE id = '$batch'");

echo $_POST['m_orderid'].'|success';
exit;
}
echo $_POST['m_orderid'].'|error';
}
?>