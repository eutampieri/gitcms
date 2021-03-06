<?php
define("DEFAULT_LOCALE", "en");
function __($str){
    $t_default_locale = json_decode(file_get_contents(dirname(dirname(__FILE__))."/locales/".DEFAULT_LOCALE.".json"), true);
    $t_conf = json_decode(file_get_contents(dirname(dirname(__FILE__))."/conf.json"), true);
    $t_locale = json_decode(file_get_contents(dirname(dirname(__FILE__))."/locales/".$t_conf["locale"].".json"), true);
    return isset($t_locale[$str]) ? $t_locale[$str] : (isset($t_default_locale[$str]) ? $t_default_locale[$str] : $str);
}

function ___($str){
    echo __($str);
}

function get_ssh_pubkey(){
    $homedir = exec("eval echo ~$USER");
    if(!is_dir($homedir."/.ssh")) exec("mkdir ".$homedir."/.ssh");
    if(!is_file($homedir."/.ssh/id_rsa.pub")) exec("cd ~/.ssh && ssh-keygen -f id_rsa -t rsa -N ''");
    return file_get_contents($homedir."/.ssh/id_rsa.pub");
}
