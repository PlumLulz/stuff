<?php
/*
Page: Ajax Music
*/
$songs = scandir("../music");
unset($songs[0]);
unset($songs[1]);
$diff = array_diff($songs, array('ogg'));
echo $diff[array_rand($diff)];
?>