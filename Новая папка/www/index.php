<?php
$pname = 'Ставки на рыбу';
$pkey = 'Ставки на рыбу';
$pdesc = 'Ставки на рыбу';
include('inc/top.php');


function itime() {
    //Текущее время
    $currentTime = time();
    $nt = date('Y:m:d:G:i:s:', $currentTime);
    list($year,$month,$day, $hour, $min, $sec) = explode(':', $nt);

    //Узнаём ближайший час
    if ($hour >= 0 && $hour < 6) { $wayth = 6; }
    if ($hour >= 6 && $hour < 12) { $wayth = 12; }
    if ($hour >= 12 && $hour < 18) { $wayth = 18; }
    if ($hour >= 18 && $hour >= 0){
        // новый день
        $day += 1;
        //проверяем месяца по 31 дню
        if ($day == 32){
            $month += 1;
            //проверяем декабрь
            if ($month == 12){
                $year += 1;
            }
        }
        //проверяем месяца по 30 дней
        elseif ($day == 31 ){
            $monthList = array(4,6,9,11);
            if (in_array($month, $monthList)){
                $month += 1;
            }

        }
        //проверяем високосный февраль
        elseif ($day == 30 && $year%4 == 0 && $month == 2){
            $month += 1;
        }
        //проверяем просто февраль
        elseif ($day == 29 && $month == 2){
            $month += 1;
        }

        $wayth = 0;
    }
    $targetTime = mktime($wayth,0,0,$month,$day,$year);

    //Считаем время до этого часа
    $result = $targetTime - $currentTime ;
    return $result;
}
$fish_1 = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT SUM(`sum`) AS `s` FROM t_play WHERE fish = '1' AND st = '1'"));
$fish_2 = mysqli_fetch_assoc(mysqli_query($connect_db, "SELECT SUM(`sum`) AS `s` FROM t_play WHERE fish = '2' AND st = '1'"));
list($fish_1_rub, $fish_1_cop) = explode('.', $fish_1['s']);
list($fish_2_rub, $fish_2_cop) = explode('.', $fish_2['s']);

$fish_1_rub = intval($fish_1_rub);
if (empty($fish_1_cop) || $fish_1_cop == 0) { $fish_1_cop = '00'; }
$fish_2_rub = intval($fish_2_rub);
if (empty($fish_2_cop) || $fish_2_cop == 0) { $fish_2_cop = '00'; }

$summed = $fish_1['s'] + $fish_2['s'];
if ($summed > 0) {
$for_percent = 100/$summed; 
$fish_1_percent = $for_percent*$fish_1['s'];
$fish_2_percent = $for_percent*$fish_2['s'];
} else {
$fish_1_percent = 50;
$fish_2_percent = 50;
}

if ($fish_1_percent < 50) { $fish_1_img = 'fish01'; }
if ($fish_1_percent >= 50 && $fish_1_percent <= 75) { $fish_1_img = 'fish00'; }
if ($fish_1_percent > 75) { $fish_1_img = 'fish02'; }

if ($fish_2_percent < 50) { $fish_2_img = 'fish11'; }
if ($fish_2_percent >= 50 && $fish_2_percent <= 75) { $fish_2_img = 'fish10'; }
if ($fish_2_percent > 75) { $fish_2_img = 'fish12'; }
?>

<div id="jackpot" class="fullWidth"<?php if (isset($_COOKIE['ok'])) { echo 'style="display:none"'; } ?>>
<div class="content">
<div class="comment">
Делайте ставки на красную или зелёную рыбу. Розыгрыш каждые 6 часов. Выигрывает та рыба, которая набрала наибольшую сумму.<br />
Все игроки, которые вкладывали в эту рыбу, получат свои инвестиции обратно плюс доля от проигравшей рыбы.<br />
Выигрыш зависит от размера ставки - чем больше вы поставите, тем больше выиграете. Число ставок не ограничено.<br />
<a href="javascript://" style="color: #2a9bf3;text-decoration: none;" id="took">Понятно</a>
</div>
</div>
</div>

<div class="fullWidth">
<div class="content">
<div id="roundStatus">
<span class="status status1" style="display: inline;">До конца раунда
<span id="remainingHours" class="remainingTimeValue">00</span> ч :
<span id="remainingMinutes" class="remainingTimeValue">00</span> м :
<span id="remainingSeconds" class="remainingTimeValue">00</span> с
<br>
</span>
</div>
</div>
</div>

<div id="fishSummary" class="fixedWidth">
<div id="fish0summary" class="fishBox">
<div class="fishImg img0" style="display: block;"><img src="/img/<?php echo $fish_1_img; ?>.png" id="ifish1" width="400" height="264" /></div>
<div class="content">
<div>На красную рыбу поставлено:</div>
<div class="balance red">
<span class="int" id="fish_1_rub"><?php echo $fish_1_rub; ?></span><span class="btc">.</span><span class="dec" id="fish_1_cop"><?php echo $fish_1_cop; ?></span>&nbsp;<span class="btc">Руб.</span>
</div>
<div class="status status1 addrTitle" style="display: block;margin-top: 5px;">

<form action="/actions/in.php" method="POST">
<input type="text" value="" placeholder="Сумма ставки" class="inp" style="width: 100px;" name="sum" required="required" />
<input type="hidden" value="1" name="fish" />
<input type="submit" value="Поставить" id="set1" class="btn-r" />
</form>

</div>
<p class="addr red"><a href="bitcoin:" class="addrLink red"></a></p>
</div>
</div>

<div id="fish1summary" class="fishBox">
<div class="fishImg img0" style="display: block;"><img src="/img/<?php echo $fish_2_img; ?>.png" id="ifish2" width="400" height="264"></div>
<div class="content">
<div>На зелёную рыбу поставлено:</div>
<div class="balance green">
<span class="int" id="fish_2_rub"><?php echo $fish_2_rub; ?></span><span class="btc">.</span><span class="dec" id="fish_2_cop"><?php echo $fish_2_cop; ?></span>&nbsp;<span class="btc">Руб.</span>
</div>
<div class="status status1 addrTitle" style="display: block;margin-top: 5px;">

<form action="/actions/in.php" method="POST">
<input type="text" value="" placeholder="Сумма ставки" class="inp" style="width: 100px;" name="sum" required="required" />
<input type="hidden" value="2" name="fish" />
<input type="submit" value="Поставить" class="btn-g" />
</form>

</div>
<p class="addr green"><a href="bitcoin:" class="addrLink green"></a></p>
</div>
</div>
</div>

<script type='text/javascript'>
imain = parseInt(1);
var a1 = parseInt(<?php echo itime(); ?>);
</script>
<script src="/js/jquery.cookie.js"></script>
<script src="/js/main.js"></script>

<?php include('inc/bottom.php'); ?>