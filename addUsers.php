<?php
$host = 'localhost';
$db = 'db_users';
$user = 'root';
$pass = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

$pdo = new PDO($dsn, $user, $pass, $options);

function addUserOfDb($login, $pass, $email): bool
{

    global $pdo;
    $login = trim(htmlspecialchars($login));
    $pass = trim(htmlspecialchars($pass));
    $email = trim(htmlspecialchars($email));
    $res = $pdo->prepare("SELECT count(*) FROM users_tab WHERE login =?");
    $res->execute([$login]);
    $tmp = $res->fetch();

    if ($tmp['count(*)'] != '0') {
        $_SESSION['error'] = 'this login already exists';

        return false;
    }

    if ($login == '' || $pass == '' || $email == '') {
        $_SESSION['error'] = 'all fields of the form must be filled in';

        return false;
    }
    if (strlen($login) < 3 || strlen($login) > 50) {

        $_SESSION['error'] = 'the login field was entered incorrectly';

        return false;
    }
    if (strlen($email) < 3 || strlen($email) > 50) {
        $_SESSION['error'] = 'the email field was entered incorrectly';

        return false;
    }
    if (strlen($pass) < 3 || strlen($pass) > 50) {
        $_SESSION['error'] = 'the password field was entered incorrectly';

        return false;
    }
    $pass = password_hash("$pass", PASSWORD_DEFAULT);
    $res = $pdo->prepare("INSERT INTO users_tab (login, password, email) VALUES (?, ?, ?)");
    $res->execute([$login, $pass, $email]);
    if ($res->rowCount() > 0) $_SESSION['success'] = 'registration completed completed successfully';
    else $_SESSION['error'] = 'request execution error';
    return true;
}

if (isset($_POST['btn_reg'])) {
    $result = addUserOfDb($_POST['login'], $_POST['password'], $_POST['email']);

}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>
<?php if (isset($_SESSION['error'])): ?>
    <h3 class="text-danger"><?= $_SESSION['error'] ?></h3>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <h3 class="text-success"><?= $_SESSION['success'] ?></h3>
<?php endif; ?>
<a type="button" href="index.php" class="btn btn-primary">Index</a>
<a type="button" href="showUsers.php" class="btn btn-primary">Show Users</a>
</body>
</html>
