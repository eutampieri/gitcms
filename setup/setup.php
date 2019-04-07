<?php
header("Location: ..");
require_once("../plugins/default_auth/main.php");

if(is_dir("../posts")){
    die();
}

$conf = json_decode(file_get_contents("../conf.json"), true);

$exclude_from_settings_file = ["repo_url", "post_storage", "username", "password", "name"];

foreach($_POST as $key => $value){
    if(in_array($key, $exclude_from_settings_file)) continue;
    $conf[$key] = $value;
}

switch ($_POST["post_storage"]) {
    case 'local':
        exec("cd .. && mkdir posts && cd posts && git init");
        break;
    case 'remote':
        passthru("cd .. && git clone ".$_POST["repo_url"]." posts");
    default:
        break;
}

$auth = new Auth();

$auth->add_user($_POST["username"], $_POST["password"], $_POST["name"]);

file_put_contents("../conf.json", json_encode($conf));
