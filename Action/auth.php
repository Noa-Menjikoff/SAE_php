<?php
session_start();

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__FILE__));
    require_once BASE_PATH . '/../BD/Database.php';

}
else{
    require_once BASE_PATH . '/BD/Database.php';
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $dbPath = '../BD/sae.sqlite3'; 
    $db = new Database($dbPath);

    $user = $db->getUserParNomUtilisateur($username);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        if ($user['username'] === 'adm') {
            $_SESSION['adm'] = TRUE;
        }
        header("Location: ../accueil.php?registration_success=1");
        exit;

    } else {
        header("Location: ../index.php?login_error=1");
        exit;
    }
} elseif (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $dbPath = '../BD/sae.sqlite3'; 
    $db = new Database($dbPath);

    $success = $db->enregistrerUtilisateur($username, $hashedPassword);

    if ($success) {
        $user = $db->getUserParNomUtilisateur($username);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user'] = $user;
        header("Location: ../accueil.php?registration_success=1");
        exit;
    } else {
        header("Location: ../index.php?registration_error=1");
        exit;
    }
}
?>
