<?php include_once "head.php"?>
<?php include_once "header.php"?>
<form method="post" action="index.php?controller=Usuario&action=login">
    <label for="usernameInput">Usuario: </label>
    <input type="text" id="usernameInput" class="form-control" name="username">
    <label for="passwordInput">Contraseña: </label>
    <input type="password" id="passwordInput" class="form-control" name="password">
    <input type="submit" value="Log-in" class="btn btn-success"/>
</form>
<?php include_once "footer.php"?>
