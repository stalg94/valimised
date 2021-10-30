<?php
$serverinimi="localhost";
$kasutaja="stas21";
$parool="123456";
$andmebaas="aleksejevski21";
$yhendus= new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset('UTF8');
?>
