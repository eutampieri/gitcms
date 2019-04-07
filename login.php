<?php
$conf = json_decode(file_get_contents("conf.json"), true);
require_once("plugins/".$conf["auth_plugin"]."/main.php");
require_once("tools/tools.php");

$error = null;
$auth = new Auth();

if(isset($_COOKIE["gitcms_session"])){
    if($auth->check_session($_COOKIE["gitcms_session"])){
        header("Location: .");
        die();
    } else{
        $error = ___("session-expired");
    }
}

if(isset($_POST["username"]) && isset($_POST["password"])){
    // Then the user is willing to login
    if($auth->login($_POST["username"], $_POST["password"])===false){
        // If the login has failed
        $error = ___("login-failed");
    } else{
        header("Location: .");
        die();
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo $conf["lang"];?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php $conf["blog_title"]." - ".___("login");?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <!--link rel="stylesheet" href="theme.css"-->
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/."><?php echo $conf["blog_title"];?></a>
    </nav>
    <div class="container">
        <h1 class="display-4"><?php ___("login");?></h1>
        <div class="alert alert-danger<?php echo $error === null ? " d-none" : "";?>" role="alert"><?php echo $error;?></div>

        <form method="POST" action="setup.php">
            <div class="form-group">
                <label for="username"><?php ___("username");?></label>
                <input type="text" class="form-control" id="username" name="username" placeholder="<?php ___("username");?>">
            </div>
            <div class="form-group">
                <label for="password"><?php ___("password");?></label>
                <input type="password" class="form-control" id="password" name="password" placeholder="<?php ___("password");?>">
            </div>
            <button type="submit" class="btn btn-primary"><?php ___("signin");?></button>
        </form>
    </div>
</body>
</html>
