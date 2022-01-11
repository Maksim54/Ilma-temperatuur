<?php
require("temp.php");
session_start();
if(isSet($_REQUEST["maakonnalisamine"])){
    if (!empty(trim($_REQUEST["uuemakonnanimi"]))) {
        lisaGrupp($_REQUEST["uuemakonnanimi"]);
        header("Location: lisamine.php");
        exit();
    }
}
if(isSet($_REQUEST["teavetlisamine"])){
    //
    if(!empty(trim($_REQUEST["temperatuur"])) && !empty(trim($_REQUEST["kuupaev"]))){
        lisaKaup($_REQUEST["temperatuur"], $_REQUEST["maakonna_id"], $_REQUEST["kuupaev"]);
        header("Location: lisamine.php");
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
$kaubad=kysiKaupadeAndmed();
if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];
}
?>
<!DOCTYPE html>
<head>
    <title>Ilma temperatuuri leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
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
<?php
if(isset($_SESSION['knimi'])){
    ?>
    <div class="header">
        <h1>Tabelid maakonnad</h1>
    </div>
    <div class="row">
        <div class="column">
            <form action="lisamine.php">
                <h2>Temperatuuri lisamine</h2>
                <dl>
                    <dt>Temperatuur:</dt>
                    <dd><input type="text" name="temperatuur" /></dd>
                    <dt>Maakonna:</dt>
                    <dd><?php
                        echo looRippMenyy("SELECT id, maakonnanimi FROM maakonna",
                            "maakonna_id");
                        ?>
                    </dd>
                    <dt>Kuupaev:</dt>
                    <dd><input type="date" name="kuupaev" /></dd>
                </dl>
                <input type="submit" name="teavetlisamine" value="Lisa teavet" />
        </div>
        <div class="column">
            <h2>Maakonna lisamine</h2>
            <input type="text" name="uuemakonnanimi" />
            <input type="submit" name="maakonnalisamine" value="Lisa maakonna" />
            </form>
        </div>
        <div class="column">
            <form action="lisamine.php">
                <h2>Maakonna loetelu</h2>
                <table>
                    <tr>
                        <th>Haldus</th>
                        <th>Temperatuur</th>
                        <th>Maakonna</th>
                        <th>Kuupaev</th>
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
                                <td><input type="number" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
                                <td><?php
                                    echo looRippMenyy("SELECT id, maakonnanimi FROM maakonna",
                                        "maakonna_id", $kaup->id);
                                    ?></td>
                                <td><input type="date" name="kuupaev" value="<?=$kaup->nimetus ?>" /></td>
                            <?php else: ?>
                                <td>
                                    <?php
                                    if(isset($_SESSION['unimi'])){
                                        ?>
                                    <a href="lisamine.php?kustutusid=<?=$kaup->id ?>"
                                       onclick="return confirm('Kas ikka soovid kustutada?')">x</a>
                                    <a href="lisamine.php?muutmisid=<?=$kaup->id ?>">m</a>
                                    <?php }?>
                                </td>
                                <td><?=$kaup->nimetus ?></td>
                                <td><?=$kaup->grupinimi ?></td>
                                <td><?=$kaup->hind ?></td>
                            <?php endif ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </form>
        </div>
    </div>
    <?php
}
?>
</body>
</html>
