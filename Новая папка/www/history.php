<?php
$pname = 'История побед';
$pkey = 'победы';
$pdesc = 'История побед';
include('inc/top.php');
?>

<div id="mainRules" class="fullWidth">
<div class="content">
<div class="information">
<?php
$page = intval($_GET['page']);
$num = 20;
if ($page==0) $page=1;
$qu = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT count(`id`) AS `cnt` FROM t_games"));
$co = $qu['cnt'];
$total = intval(($co - 1) / $num) + 1;
$page = intval($page);
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
$start = $page * $num - $num;
if ($page != 1) $pervpage = '<a href="/history?page='. ($page - 1).'">Предыдущая страница</a> ';
if ($page != $total) $nextpage = '<a href="/history?page='. ($page + 1).'">Следующая страница</a>';
$qr = mysqli_query($connect_db, "SELECT id,f,s1,s2,dt FROM t_games ORDER BY id DESC LIMIT $start, $num");
if ($co > 0) {
?>
<table style="width: 100%;">
<thead>
<tr>
<th class="text-center">Победившая рыба</th>
<th class="text-center">Поставлено на красную</th>
<th class="text-center">Поставлено на зелёную</th>
<th class="text-center">Дата победы</th>
</tr>
</thead>
<tbody>
<?php while($rowi = mysqli_fetch_array($qr)) { ?>
<tr>
<td><img src="/img/<?php if ($rowi['f'] == 1) { echo 'fish03'; } if ($rowi['f'] == 2) { echo 'fish13'; } if ($rowi['f'] == 3) { echo 'fishx4'; } ?>.png" style="max-width:70px" /></td>
<td><?php echo $rowi['s1']; ?> руб.</td>
<td><?php echo $rowi['s2']; ?> руб.</td>
<td><?php echo date('d.m.y H:i',$rowi['dt']); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
<?php
if ($total>1) { echo $pervpage.$nextpage; }
} else { echo '<center>Игр нет</center>'; } ?>
</div>

</div>
</div>

<?php include('inc/bottom.php'); ?>