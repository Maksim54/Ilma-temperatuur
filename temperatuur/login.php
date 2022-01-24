<?php
session_start();
$yhendus=new mysqli("localhost", "root", "", "test");
//login vorm Andmebaasis salvestatud kasutajanimega ja prooliga
$error= $_SESSION["error"] ?? "";
// kontroll kas login vorm on täidetud?
if(isset($_REQUEST['knimi']) && isset($_REQUEST['psw'])) {
    $login = htmlspecialchars($_REQUEST['knimi']);
    $pass = htmlspecialchars($_REQUEST['psw']);
    $sool = 'vagavagatekst';
    $krypt = crypt($pass, $sool);
    // kontrollime kas andmebaasis on selline kasutaja
    $kask = $yhendus->prepare("SELECT id, knimi, psw, isadmin FROM uuedkasutajad WHERE knimi=?");
    $kask->bind_param("s", $login);
    $kask->bind_result($id, $kasutajanimi, $parool, $onadmin);
    $kask->execute();
    if ($kask->fetch() && $krypt == $parool) {
        $_SESSION['knimi'] = $login;
        if ($onadmin == 1) {
            $_SESSION['admin'] = true;
            header("Location: lisamine.php");
        }
        echo "kasutaja $login või parool $krypt on vale";
        $yhendus->close();
    }
}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style1.css" type="text/css">
</head>
<body>
<h1>Login vorm</h1>
<div class="modal">
<form action="login.php" method="post" class="modal-content">
    <label for="knimi">Kasutajanimi</label>
    <input type="text" placeholder="Sisesta kasutajanimi"
           name="knimi" id="knimi" required>
    <br>
    <label for="psw">Parool</label>
    <input type="password" placeholder="Sisesta parool"
           name="psw" id="psw" required>
    <br>
    <input type="submit" value="Logi sisse">
      <button type="button"
              onclick="window.location.href='lisamine.php'";
              class="cancelbtn">Cancel</button>
<strong> <?=$error?></strong>
</form>
</div>
</body>