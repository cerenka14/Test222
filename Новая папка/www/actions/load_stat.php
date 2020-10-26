<?php
include('../inc/conf.php');
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

echo itime().'|'.$fish_1_rub.'|'.$fish_1_cop.'|'.$fish_2_rub.'|'.$fish_2_cop.'|'.$fish_1_img.'|'.$fish_2_img;
?>