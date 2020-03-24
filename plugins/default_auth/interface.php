<?php

interface AuthPlugin{
    public function check_credentials($user, $password);
    public function check_session($session_id);
    public function login($user, $password);
    public function get_user_info($session_id);
    public function create_session($user_id);
}