<?php
require("temp.php");
$sorttulp="temperatuur";
$otsisona="";
if(isSet($_REQUEST["maakonnaisamine"])){
    if (!empty(trim($_REQUEST["uuemakonnanimi"]))) {
        lisaGrupp($_REQUEST["uuemakonnanimi"]);
        header("Location: lisaVaata.php");
        exit();
    }
}
if(isSet($_REQUEST["teavetlisamine"])) {
    if (!empty(trim($_REQUEST["temperatuur"])) && !empty(trim($_REQUEST["kuupaev"]))) {
        lisaKaup($_REQUEST["temperatuur"], $_REQUEST["maakonna_id"], $_REQUEST["kuupaev"]);
        header("Location: lisaVaata.php");
        exit();
    }
}
if(isSet($_REQUEST["kustutusid"])){
    kustutaKaup($_REQUEST["kustutusid"]);
}
if(isSet($_REQUEST["muutmine"])){
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"],
        $_REQUEST["maakonna_id"], $_REQUEST["kuupaev"]);
}
if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];
}
$kaubad=kysiKaupadeAndmed($sorttulp, $otsisona);
?>
<!DOCTYPE html>
<div id="menuArea">
    <a href="registr.php">Loo uus kasutaja</a>
    <?php
    if(isset($_SESSION['knimi'])){
        ?>
        <h1>Tere, <?="$_SESSION[knimi]"?></h1>
        <a href="logout1.php">Logi välja</a>
        <?php
    } else {
        ?>
        <a href="login1.php">Logi sisse</a>
        <?php
    }
    ?>
</div>
<head>
    <div class="header">
        <title>Temperaturi halduse leht</title>
    </div>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="row">
    <div class="header">
        <form action="lisaVaata.php">
            <h2>Maakonda lisamine</h2>
    </div>
    <div class="column">
        <dl>
            <dt>Temperatuur❄️:</dt>
            <br>
            <dd><input type="text" name="temperatuur" /></dd>
            <br>
            <dt>Maakonna🏙️:</dt>
            <br>
            <dd><?php
                echo looRippMenyy("SELECT id, maakonnanimi FROM maakonna",
                    "maakonna_id");
                ?>
            </dd>
            <br>
            <dt>Kuupäev🕒:</dt>
            <br>
            <dd><input type="date" name="kuupaev" /></dd>
        </dl>
        <input class="bt" type="submit" name="teavetlisamine" value="Lisa teavet" />
    </div>
    <div class="column">
        <h2>Maakonda lisamine</h2>
        <input   type="text" name="uuemakonnanimi" />
        <br>
        <input class="bt" type="submit" name="maakonnaisamine" value="Lisa maakonna" />
        </form>
    </div>
    <div class="column2">
        <form action="lisaVaata.php">
            <h2>Maakonna loetelu</h2>
            <table>
                <tr>
                    <th>Haldus</th>
                    <th><a href="lisaVaata.php?sort=temperatuur">Temperatuur</a></th>
                    <th><a href="lisaVaata.php?sort=maakonnanimi">Maakonna</a></th>
                    <th><a href="lisaVaata.php?sort=kuupaev">Kuupaev</a></th>
                </tr>
                <?php foreach($kaubad as $kaup): ?>
                    <tr>
                        <?php if(isSet($_REQUEST["muutmisid"]) &&
                            intval($_REQUEST["muutmisid"])==$kaup->id): ?>
                            <td>
                                <input type="submit" name="muutmine" value="Muuda" />
                                <input type="submit" name="katkestus" value="Katkesta" />
                                <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                            </td>
                            <td><input  type="number" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
                            <td><?php
                                echo looRippMenyy("SELECT id, maakonnanimi FROM maakonna",
                                    "maakonna_id", $kaup->id);
                                ?></td>
                            <td><input type="date" name="kuupaev" value="<?=$kaup->kuupaev ?>"></td>
                        <?php else: ?>
                            <td><a2 href="lisaVaata.php?kustutusid=<?=$kaup->id ?>"
                                   onclick="return confirm('Kas ikka soovid kustutada?')">🗑️</a2>
                                <a2 href="lisaVaata.php?muutmisid=<?=$kaup->id ?>">✏️</a2>
                            </td>
                            <td><?=$kaup->nimetus ?></td>
                            <td><?=$kaup->grupinimi ?></td>
                            <td><?=$kaup->hind ?></td>
                        <?php endif ?>
                    </tr>
                <?php endforeach; ?>
            </table>
    </div>
    </form>
</div>
</div>
</body>
</html>