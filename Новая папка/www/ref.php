<?php
$pname = 'Рефералы';
$pkey = 'рефералы';
$pdesc = 'Рефералы';
include('inc/top.php');
?>

<div id="mainRules" class="fullWidth">
<div class="content">
<br /><br />
<center>
<input type="text" title="Номер вашего кошелька" value="<?php if (isset($_COOKIE['ipurse'])) { echo strip_tags($_COOKIE['ipurse']); } else { echo 'P'; } ?>" id="num" class="inp" style="width: 100px;" />
<br /><br />
<input type="text" title="Ваша реферальная ссылка" value="<?php if (isset($_COOKIE['ipurse'])) { echo 'http://'.SITE.'/?q='.base64_encode(strip_tags($_COOKIE['ipurse'])); } ?>" id="ref" class="inp" style="width: 300px;<?php if (!isset($_COOKIE['ipurse'])) { echo ' display:none'; } ?>" onclick="this.select()" readonly />
</center>
<br /><br />

<div class="information">
Вы получаете 5% от выигрыша ваших друзей, которые перешли по вашей реферальной ссылке. 
Введите свой Payeer кошелёк в поле выше для получения рефссылки. 
Статистика по приглашённым пользователям отображается ниже. Реферал закрепляется за вами при совершении первой ставки. 
Если вы хотите посмотреть статистику по другому своему Payeer кошельку, просто введите его номер в поле выше и обновите страницу.
</div>
<br /><br />
<?php if (isset($_COOKIE['ipurse'])) { ?>
<div class="information">
<?php
$iam = mysqli_real_escape_string($connect_db, $_COOKIE['ipurse']);
$qin = mysqli_query($connect_db, "SELECT id,ref,prf,dt FROM t_ref WHERE usr = '$iam' ORDER BY id DESC");
$how = mysqli_num_rows($qin);
if ($how > 0) { ?>
<table style="width: 100%;">
<thead>
<tr>
<th class="text-center">Пользователь</th>
<th class="text-center">Прибыль</th>
<th class="text-center">Присоединился</th>
</tr>
</thead>
<tbody>
<?php
while($rowi = mysqli_fetch_array($qin)) {
?>
<tr>
<td><?php echo substr_replace($rowi['ref'],'**',-2); ?></td>
<td><?php echo $rowi['prf']; ?> руб.</td>
<td><?php echo date('d.m.y H:i',$rowi['dt']); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php
} else { echo '<center>Рефералов нет</center>'; }
?>
</div>
<?php } ?>

</div>
</div>

<script src="/js/jquery.cookie.js"></script>
<script src="/js/jquery.base64.js"></script>
<script type='text/javascript'>
$(function() {
$('#num').bind("change keyup paste input", function() {
$('#ref').show('slow');
var ipurse = $('#num').val();
if ((ipurse.indexOf('P') + 1) == 0) { alert ('Кошелёк должен содержать букву P'); $('#num').val('P'); return false; }
$.cookie('ipurse', ipurse);
var b64 = $.base64.btoa(ipurse);
$('#ref').val('http://<?php echo SITE; ?>/?q='+b64);
});
});
</script>

<?php include('inc/bottom.php'); ?>