<?php
require_once ('conf.php');
global $yhendus;
if(!empty($_REQUEST['uusnimi'])){
    $kask=$yhendus->prepare('INSERT INTO valimised(nimi, lisamisaeg)
    Values (?, Now())');
    $kask->bind_param('s', $_REQUEST['uusnimi']);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    //$yhendus->close();
}


//peitmine, avalik=0
if(isset($_REQUEST["peitmine"])) {
    $kask = $yhendus->prepare('
    UPDATE valimised SET avalik=0 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["peitmine"]);
    $kask->execute();
}
//avalikustamine, avalik=1
if(isset($_REQUEST["avamine"])) {
    $kask = $yhendus->prepare('
    UPDATE valimised SET avalik=1 WHERE id=?');
    $kask->bind_param('i', $_REQUEST["avamine"]);
    $kask->execute();
}
if(isset($_REQUEST["kustuta"])) {
    $kask = $yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}

?>
    <!Doctype html>
    <html lang="et">
    <head>
        <title>Haldusleht</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="style/style.css">
    </head>
    <body>
    <?php include ('navigation.php');?>
    <h2>Uue nimi lisamine</h2>
    <form action="?">
        <label for="uusnimi">Nimi</label>
        <input type="text" id="uusnimi" name="uusnimi" placeholder="uus nimi">

        <input type="submit" value="Lisa">
    </form>
    <h1>Valimisnimede haldus</h1>
    <?php
    //valimiste tabeli sisu vaatamine andmebaasist
    global $yhendus;
    $kask=$yhendus->prepare('
    SELECT id, nimi, avalik FROM valimised');
    $kask->bind_result($id, $nimi, $avalik);
    $kask->execute();
    echo "<table>";
    echo "<tr><th>Nimi</th><th>Seisund</th><th>Tegevus</th><th>Kustuta</th>";

    while($kask->fetch()){
        $avatekst="Ava";
        $param="avamine";
        $seisund="Peidetud";
        if($avalik==1){
            $avatekst="Peida";
            $param="peitmine";
            $seisund="Avatud";
        }

        echo "<tr>";
        echo "<td>".htmlspecialchars($nimi)."</td>";
        echo "<td>".($seisund)."</td>";
        echo "<td><a href='?$param=$id'>$avatekst</a></td>";
        echo "<td><span class='letter'><a href='".strtok(basename($_SERVER['REQUEST_URI']),"&")."&kustuta=$id'>&#10060;</a></span></td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    </body>
    </html>
<?php
$yhendus->close();
