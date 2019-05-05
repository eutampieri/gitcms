<?php
require_once("auth_interface.php");

$auth_db = new PDO("sqlite:res/dati-menu.db");
$auth_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$auth_init_queries = [
    "CREATE TABLE IF NOT EXISTS user (username TEXT PRIMARY KEY, `password` TEXT, `name` TEXT);",
    "CREATE TABLE IF NOT EXISTS `session` (id TEXT, `user_id` TEXT, backend TEXT, expiration INT)"
];
foreach($auth_init_queries as $qry){
    $stmt = $auth_db->prepare($qry);
    $stmt->execute();
}

class Auth implements AuthPlugin
{
    private $session_duration = 12*3600; // 12 hours

    public $rw_users = true;

    public function check_credentials($user, $password){
        $stmt = $auth_db->prepare("SELECT * FROM user WHERE username = :user");
        $stmt->bindParam(":user", $user);
        $stmt->execute();
        $found_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($found_users) !=1 ) return false;
        if(password_verify($password, $found_users[0]["password"])) return false;
        return $user;
    }

    public function check_session($session_id){
        $stmt = $auth_db->prepare("SELECT * FROM `session` WHERE id = :id AND expiration > :time_now AND backend = 'default_auth'");
        $stmt->bindValue(":time_now", time());
        $stmt->bindParam(":id", $session_id);
        $found_session = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return count($found_session) > 0 ? $found_session : false;
    }

    public function create_session($user_id){
        $session_id = uniqid();
        $stmt = $auth_db->prepare("INSERT INTO `session` VALUES(:id, :uid, 'default_auth', :exp)");
        $stmt->bindValue(":exp", time()+$this->session_duration);
        $stmt->bindParam(":id", $session_id);
        $stmt->bindParam(":uid", $user_id);
        $stmt->execute();
        return $session_id;
    }

    public function login($username, $password){
        $user_id = check_credentials($username, $password);
        if($user_id !== false){
            $session_id = $this->create_session($user_id);
            setcookie("gitcms_session", $session_id, time()+$this->session_duration);
        }
        return $user_id;
    }

    public function ger_user_info($session_id){
        $stmt = $auth_db->prepare("SELECT `name` as display_name FROM `session`, user WHERE backend='default_auth' AND `user_id` = username AND id = :id LIMIT 1");
        $stmt->bindParam(":id", $session_id);
        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

    public function add_user($username, $password, $name){
        $stmt = $auth_db->prepare("INSERT INTO user VALUES (:id, :username, :password, :name)");
        $user_id = uniqid();
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bindParam(":id", $user_id);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $user_id);
        $stmt->bindParam(":name", $name);
        $stmt->execute();
    }
}
