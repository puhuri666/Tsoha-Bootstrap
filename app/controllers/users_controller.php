<?php

class UserController extends BaseController {

    public static function index() {
        self::check_admin_status();
        $users = User::all();
        $user = BaseController::get_user_logged_in();
        View::make('user/index.html', array('users' => $users, 'user' => $user));
    }

    public static function show($id) {
        self::check_admin_status();
        $user = User::find($id);
        $wagers = WagerController::find($id);
        View::make('user/inspect.html', array('user' => $user, 'wagers' => $wagers));
    }

    public static function add() {
        self::check_admin_status();
        $user = BaseController::get_user_logged_in();
        View::make('user/new.html', array('user' => $user));
    }

    public static function controlpanel() {
        self::check_logged_in();
        $user = BaseController::get_user_logged_in();
        $wagers = WagerController::find($_SESSION['user']);
        View::make('user/controlpanel.html', array('user' => $user, 'wagers' => $wagers));
    }

    public static function create() {
        self::check_admin_status();
        $params = $_POST;

        $user = new User(array(
            'firstname' => $params['firstname'],
            'lastname' => $params['lastname'],
            'email' => $params['email'],
            'password' => $params['password'],
            'address' => $params['address'],
            'zipcode' => $params['zipcode'],
            'town' => $params['town']
        ));

        if ($params['firstname'] !== '' || $params['lastname'] || '' || $params['email'] !== '' || $params['password'] !== '' || $params['address'] !== '' || $params['zipcode'] || '' && $params['town'] !== '') {
            $user->save();
            Redirect::to('/users/' . $user->id, array('message' => 'Käyttäjä lisätty!'));
        } else {
            Redirect::to('/users/new    ', array('message' => 'Täytä kaikki kentät!'));
        }
    }

    public static function login() {
        $user = BaseController::get_user_logged_in();
        View::make('user/login.html', array('user' => $user));
    }

    public static function handle_login() {
        $params = $_POST;
        $user = User::authenticate($params['email'], $params['password']);
        if (!$user) {
            View::make('user/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'email' => $params['email']));
        } else {
            $_SESSION['user'] = $user->id;
            Redirect::to('/');
        }
    }

    public static function logout() {
        $_SESSION['user'] = null;
        Redirect::to('/', array('message' => 'Olet kirjautunut ulos!'));
    }

    public static function update() {
        self::check_logged_in();
        $user = self::get_user_logged_in();
        $params = $_POST;
        $updatedUser = new User(array(
            'id' => $user->id,
            'address' => $params['address'],
            'zipcode' => $params['zipcode'],
            'town' => $params['town']
        ));
        $errors = $updatedUser->validateAddressInput();
        if (count($errors) == 0) {
            $updatedUser->updateAddress();
            Redirect::to('/controlpanel', array('message' => 'Osoite päivitetty!'));
        } else {
            Redirect::to('/controlpanel', array('errors' => $errors));
        }
    }

    public static function deleteUser($id) {
        self::check_admin_status();
        if ($id == 1) {
            Redirect::to('/users', array('message' => 'Puuhaat jotain todella hämärää'));
        }

        self::check_logged_in();
        $user = self::get_user_logged_in();
        if ($user->superuser) {
            $userToDelete = new User(array(
                'id' => $id
            ));
            $userToDelete->destroy();
            Redirect::to('/users', array('message' => 'Käyttäjä poistettu!'));
        }
    }

}
