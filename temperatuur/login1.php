<?php
$yhendus=new mysqli("localhost", "blinov", "12345", "blinov");
//login vorm Andmebaasis salvestatud kasutajanimega ja prooliga
session_start();

// kontroll kas login vorm on täidetud?
if(isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])) {
    $login = htmlspecialchars($_REQUEST['knimi']);
    $pass = htmlspecialchars($_REQUEST['psw']);

    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    // kontrollime kas andmebaasis on selline kasutaja
    $kask = $yhendus->prepare("
SELECT id, knimi, psw, isadmin FROM uuedkasutajad WHERE knimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool, $onadmin);
    $kask->execute();

    if ($kask->fetch() && $krypt == $parool) {
        $_SESSION['knimi'] = $login;
        if ($onadmin == 1) {
            $_SESSION['admin'] = true;
            header("Location: lisaVaata.php");
        }
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }
}
$kask = $yhendus->prepare("
INSERT INTO uuedkasutajad(knimi, psw, isadmin) 
VALUES (?,?,?)");
$kask->bind_param("ssi", $login, $krypt, $_REQUEST["admin"]);
$kask->execute();
$_SESSION['knimi'] = $login;
$_SESSION['admin'] = true;
$yhendus->close();
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css" type="text/css">
</head>
<body>
<h1>Login vorm</h1>

<div class="modal">
    <form action="login1.php" method="post" class="modal-content animate">
        <label for="knimi">Kasutajanimi</label>
        <input type="text" placeholder="Sisesta kasutajanimi"
               name="knimi" id="knimi" required>
        <br>
        <label for="psw">Parool</label>
        <input type="password" placeholder="Sisesta parool"
               name="psw" id="psw" required>
        <br>
        <label for="admin">Kas teha admin?</label>
        <input type="checkbox" name="admin" id="admin" value="1">
        <br>
        <input type="submit" value="Loo kasutaja">
        <button type="button"
                onclick="window.location.href='lisaVaata.php'"
                class="cancelbtn">Loobu</button>
</form>
</div>
</body>