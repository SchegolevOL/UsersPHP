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

function getUsersDb():array
{
    global $pdo;
    $res = $pdo->prepare("SELECT login,email FROM users_tab");
    $res->execute();
    $result = [];
    while ($row = $res->fetch()) {
        $result[] = $row;
    }
    return $result;
}
$users = getUsersDb();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" >
    <title>Document</title>
</head>
<body>
<div class="container">
    <table class="table table-primary">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Login</th>
            <th scope="col">Email</th>
        </tr>
        </thead>
        <?php $i =1; foreach ($users as $user):?>
        <tbody>
        <tr>
            <th scope="row"><?=$i++?></th>
            <td><?=$user['login']?></td>
            <td><?=$user['email']?></td>

        </tr>
        <?php endforeach;?>
    </table>
</div>
</body>
</html>
