<?php

class BaseController {

    public static function get_user_logged_in() {
        if (isset($_SESSION['user'])) {
            $id = $_SESSION['user'];
            $user = User::find($id);
            return $user;
        }

        return null;
    }

    public static function check_logged_in() {
        if (!isset($_SESSION['user'])) {
            Redirect::to('/login', array('message' => 'Kirjaudu ensin sisään!'));
        }
    }
    
    public static function check_admin_status() {
        $user = self::get_user_logged_in();
        if (!$user->superuser) {
            Redirect::to('/', array('message' => 'Pääsy evätty!'));
        }
    }
    
}
