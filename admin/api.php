<?php
$conf = json_decode(file_get_contents(dirname(dirname(__FILE__))."/conf.json"), true);
require_once(dirname(dirname(__FILE__))."/plugins/".$conf["auth_plugin"]."/main.php");
require_once(dirname(dirname(__FILE__))."/tools/tools.php");

$user = null;
$auth = new Auth();

if(isset($_COOKIE["gitcms_session"])){
    $user = $auth->check_session($_COOKIE["gitcms_session"]);
    if($user === false){
        header("Location: ../login.php");
        die();
    }
} else{
    header("Location: ../login.php");
    die();
}

switch($_REQUEST["action"]){
    case "update":
        exec("cd .. && git pull");
        header("Location: .");
        break;
    case "set_key":
        $conf[$_REQUEST["key"]] = $_REQUEST["value"];
        file_put_contents(dirname(dirname(__FILE__))."/conf.json", json_encode($conf));
        break;
    default:
        break;
}