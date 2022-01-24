<?php
session_start();
require("temp.php");
$sorttulp="temperatuur";
$otsisona="";
if(isSet($_REQUEST["maakonnalisamine"])){
    if (!empty(trim($_REQUEST["uuemakonnanimi"]))) {
        lisaGrupp($_REQUEST["uuemakonnanimi"]);
        header("Location: lisamine.php");
        exit();
    }
}
if(isSet($_REQUEST["maakonnalisamise"])){
    if (!empty(trim($_REQUEST["uuemaakonnakeskus"]))) {
        lisaGruppi($_REQUEST["uuemaakonnakeskus"]);
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
if(isSet($_REQUEST["muutmine"])&& $_SESSION['onAdmin']==1){
    muudaKaup($_REQUEST["muudetudid"], $_REQUEST["nimetus"],
        $_REQUEST["maakonna_id"], $_REQUEST["kuupaev"]);
}

if(isSet($_REQUEST["sort"])){
    $sorttulp=$_REQUEST["sort"];
}
if(isSet($_REQUEST["otsisona"])){
    $otsisona=$_REQUEST["otsisona"];

}
$kaubad=kysiKaupadeAndmed($sorttulp,$otsisona);
?>
<!DOCTYPE html>
<head>
    <title>Temperatuuri halduse leht</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<div id="linkArea" style="position:absolute; top:18px ;right:50px;">
    <a href="XML/xml/index.php">tempindex.php</a><br>
    <a href="XML/xml/uudised.php">uudised.php</a><br>
    <a href="XML/xml/andmed.xml">andmed.xml</a>
</div>
<body>
<div id="menuArea">
    <br>
    <a href="registr.php">Loo uus kasutaja</a>
    <br>
    <?php
    if(isset($_SESSION['knimi'])){
        ?>
       <a href="logout.php">Logi välja</a>
        <h1>Tere, <?="$_SESSION[knimi]"?></h1>
        <?php
    } 
    else {
        ?>
        <a href="login.php">Logi sisse</a>
        <?php
    }
    ?>
</div>
    <div class="header">
        <h1>Temperatuuri Eestis</h1>
    </div>
    <div class="row">
        <div class="column">
            <form action="lisamine.php">
            <?php
                                        if(isset($_SESSION['knimi'])){
                                            ?>
                <h2>Temperatuur lisamine</h2>
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
                <?php }?>
        </div>
        <div class="column">
        <?php
        if(isset($_SESSION['knimi'])){
        ?>
            <h2>Maakonna lisamine</h2>
            <input type="text" name="uuemakonnanimi" />
            <input type="submit" name="maakonnalisamine" value="Lisa maakonna" />
            <?php }?>
                                        
            <h3>Otsi </h3>
        <input type="text" name="otsisona" style="margin-top: -5px;">
            
            

            </form>
        </div>
        <div class="column">
            <form action="lisamine.php">
                <h2>Maakonna loetelu</h2>
                <table>
                    <tr>
                    <?php
                    if(isset($_SESSION['knimi'])){
                                            ?>
                        <th>Haldus</th>
                    <?php }?>
                        <th><a href="lisamine.php?sort=temperatuur">Temperatuur</a></th>
                        <th><a href="lisamine.php?sort=maakonnanimi">Maakond</th>
                        <th><a href="lisamine.php?sort=maakonnakeskus">Kuupaev</th>
                    </tr>
                    <?php foreach($kaubad as $kaup): ?>
                
                        <tr>
                            <?php if(isSet($_REQUEST["muutmisid"]) &&
                                intval($_REQUEST["muutmisid"])==$kaup->id):  ?>
                                <td>
                                <?php
                                if(isset($_SESSION['knimi'])){
                                            ?>
                                    <input type="submit" name="muutmine" value="Muuda" />
                                    <input type="submit" name="katkestus" value="Katkesta" />
                                    <input type="hidden" name="muudetudid" value="<?=$kaup->id ?>" />
                                </td>
                                <?php }?>
                                <td><input type="number" name="nimetus" value="<?=$kaup->nimetus ?>" /></td>
                                <td><?php
                                    echo looRippMenyy("SELECT id, maakonnanimi FROM maakonna",
                                        "maakonna_id", $kaup->id);
                                    ?></td>
                                <td><?php
                                    echo looRippMenyy("SELECT id,  maakonnakeskus FROM maakonna",
                                        "maakonna_id", $kaup->id);
                                    ?></td>
                                <td><input type="date" name="kuupaev" value="<?=$kaup->nimetus ?>" /></td>
                            <?php else: ?>
                            <?php
                                        if(isset($_SESSION['knimi'])){
                                            ?>
                                <td>
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
</body>
</html>