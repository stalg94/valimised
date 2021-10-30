<?php
require_once ('conf.php');
global $yhendus;
// uue nimi lisamine
//echo "<script>alert('Kogus peab olema rohkem kui 0!');</script>";
//Update käsk +
if(isset($_REQUEST["haal"])) {
    $kask = $yhendus->prepare('
    UPDATE valimised SET punktid=punktid + 1 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["haal"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}
//Update käsk -
if(isset($_REQUEST["minus"])) {
    $kask = $yhendus->prepare('
      UPDATE valimised SET punktid=punktid - 1 WHERE id=? and punktid > 0');
    $kask->bind_param('i', $_REQUEST["minus"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
}

?>
    <!Doctype html>
    <html lang="et">
    <head>
        <title>Valimiste leht</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
    <?php include ('navigation.php');?>
    <h1>Valimiste leht</h1>


    <?php
    //valimiste tabeli sisu vaatamine andmebaasist
    global $yhendus;
    $kask=$yhendus->prepare('
    SELECT id, nimi, punktid FROM valimised WHERE avalik=1');
    $kask->bind_result($id, $nimi, $punktid);
    $kask->execute();
    echo "<table>";
    echo "<tr><th>Nimi</th><th>Punktid</th><th>Anna oma hääl</th><th>Võta hääl ära</th>";

    while($kask->fetch()){
        echo "<tr>";
        echo "<td>".htmlspecialchars($nimi)."</td>";
        echo "<td>".($punktid)."</td>";
        echo "<td><a href='?haal=$id'>Lisa +1 punkt</a></td>";
        echo "<td><span class='letter'<a href='?minus=$id'>Võta -1 punkt</a></span></td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    </body>
    </html>
<?php
$yhendus->close();