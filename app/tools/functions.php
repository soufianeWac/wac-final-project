<?php
namespace Silex\Form;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\File;

function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
    /*
    $interval can be:
    yyyy - Number of full years
    q - Number of full quarters
    m - Number of full months
    y - Difference between day numbers
        (eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
    d - Number of full days
    w - Number of full weekdays
    ww - Number of full weeks
    h - Number of full hours
    n - Number of full minutes
    s - Number of full seconds (default)
    */
    
    if (!$using_timestamps) {
        $datefrom = strtotime($datefrom, 0);
        $dateto = strtotime($dateto, 0);
    }
    $difference = $dateto - $datefrom; // Difference in seconds
     
    switch($interval) {
     
    case 'yyyy': // Number of full years
        $years_difference = floor($difference / 31536000);
        if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
            $years_difference--;
        }
        if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
            $years_difference++;
        }
        $datediff = $years_difference;
        break;
    case "q": // Number of full quarters
        $quarters_difference = floor($difference / 8035200);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $quarters_difference--;
        $datediff = $quarters_difference;
        break;
    case "m": // Number of full months
        $months_difference = floor($difference / 2678400);
        while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
            $months_difference++;
        }
        $months_difference--;
        $datediff = $months_difference;
        break;
    case 'y': // Difference between day numbers
        $datediff = date("z", $dateto) - date("z", $datefrom);
        break;
    case "d": // Number of full days
        $datediff = floor($difference / 86400);
        break;
    case "w": // Number of full weekdays
        $days_difference = floor($difference / 86400);
        $weeks_difference = floor($days_difference / 7); // Complete weeks
        $first_day = date("w", $datefrom);
        $days_remainder = floor($days_difference % 7);
        $odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
        if ($odd_days > 7) { // Sunday
            $days_remainder--;
        }
        if ($odd_days > 6) { // Saturday
            $days_remainder--;
        }
        $datediff = ($weeks_difference * 5) + $days_remainder;
        break;
    case "ww": // Number of full weeks
        $datediff = floor($difference / 604800);
        break;
    case "h": // Number of full hours
        $datediff = floor($difference / 3600);
        break;
    case "n": // Number of full minutes
        $datediff = floor($difference / 60);
        break;
    default: // Number of full seconds (default)
        $datediff = $difference;
        break;
    }    
    return $datediff;
}

function formatDatePost($date)
{
    if(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false),'-') >= 525600){
        $newDate = 'Posté il y a '.ltrim(datediff('yyyy', date("j-m-Y H:i:s"), $date, false), '-').'ans';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-') >= 43800){
        $newDate = 'Posté il y a '.ltrim(datediff('m', date("j-m-Y H:i:s"), $date, false), '-').'mois';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false),'-') >= 10080) {
        $newDate = 'Posté il y a '.ltrim(datediff('ww', date("j-m-Y H:i:s"), $date, false), '-').'sem';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-') >= 1440) {
        $newDate = 'Posté il y a '.ltrim(datediff('d', date("j-m-Y H:i:s"), $date, false), '-').' j';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-') >= 60){
        $newDate = 'Posté il y a '.ltrim(datediff('h', date("j-m-Y H:i:s"), $date, false), '-').' h';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-') == 0){
        $newDate = 'Posté il y a moins d\'une min';
    }
    elseif(ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-') < 60){
        $newDate = 'Posté il y a '.ltrim(datediff('n', date("j-m-Y H:i:s"), $date, false), '-').'min';
    }
    else{
        $newDate = 'Posté le '.date('j-m-Y', strtotime($date));
    }
    return $newDate;
}

function renderNbrOfVideoInCategory($app)
{
    $arrayCategory = [
        'Chanson' => countVideoCategory($app, 1),
        'Tutoriel' => countVideoCategory($app, 2),
        'Politique' => countVideoCategory($app, 3),
        'Culture' => countVideoCategory($app, 4),
        'Cinema' => countVideoCategory($app, 5),
        'Jeux&tech' => countVideoCategory($app, 6),
        'Sport' => countVideoCategory($app, 7),
        'Divers' => countVideoCategory($app, 8)
    ];
    return $arrayCategory;
}

function rmFolder($dir) { 
    if (is_dir($dir)) { 
        $objects = scandir($dir); 
        foreach ($objects as $object) { 
           if ($object != "." && $object != "..") 
           { 
            if (is_dir($dir."/".$object))
                rmFolder($dir."/".$object);
            else
               unlink($dir."/".$object); 
            } 
        }
        rmdir($dir); 
    } 
}

function process($action){
    $res = [];
    if($action)
    {
        $res['request'] = 'valid';
    }else{
        $res['request'] = 'notvalid';
    }
    return json_encode($res);
}