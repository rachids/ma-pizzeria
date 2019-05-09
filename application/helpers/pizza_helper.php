<?php

function pizzaMoney(){
    return '<img src="'.base_url('assets/img/money.png').'" alt="$" title="$"/>';
}

function truncateMessage($message, $limite = 200){

// Find what is the last character.
    $post = substr($message,$limite,1);

    #Si post vaut FALSE alors pas besoin de tronquer
    if(!$post) {
        return $message;
    }
// Find until we found that last character is " "(space)
// by $position+1 (14+1=15, 15+1=16 until we found " "(space) that mean character no.20)
    elseif($post !=" "){
        while($post !=" "){
            $i=1;
            $limite=$limite+$i;

            $post = substr($message,$limite,1);
        }
    }

    $post = substr($message,0,$limite);

    return $post.'...';
}

function formatDate($date, $format = 'd/m/Y') {
    $date = new DateTime($date);

    return $date->format($format);
}

function isPluriel($nb, $singulier, $pluriel) {
    if($nb > 1) {
        return $nb.' '.$pluriel;
    }
    return $nb .' '. $singulier;
}

function differenceDate($date1, $mesure = 'heures') {
    $end = new DateTime($date1);
    $end = $end->modify( '+1 day' );

    $now = new DateTime("now");

    if($now > $end) {
        return true;
    } else {
        $diff = $end->diff($now);

        $hours = $diff->h;
        $hours = $hours + ($diff->days*24);

        $result = array(
            'h' =>  $hours,
            'm' =>  $diff->i
        );

        return $result;
    }
}