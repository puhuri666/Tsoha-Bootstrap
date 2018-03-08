<?php

class HelloWorldController extends BaseController {

    public static function index() {
        // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
        echo 'viikon 3 malliluokan toteutus löytyy osoitteesta <a href="http://pmarkkol.users.cs.helsinki.fi/tsohabootstrap/users">http://pmarkkol.users.cs.helsinki.fi/tsohabootstrap/users</a>';
    }

    public static function etusivu() {
        View::make('suunnitelmat/etusivu.html');
    }

    public static function vedonlyonti() {
        View::make('suunnitelmat/vedonlyonti.html');
    }

    public static function omasivu() {
        View::make('suunnitelmat/oma_sivu.html');
    }

    public static function ehdotus() {
        View::make('suunnitelmat/ehdotus.html');
    }

    public static function passwdreset() {
        View::make('suunnitelmat/passwdreset.html');
    }

    public static function sandbox() {
        $teamNames = Matchup::getTeamNames(25);
        Kint::dump($teamNames);
        Kint::dump($teamNames[0]);
        Kint::dump($teamNames[1]);
        $wagers = WagerController::find(3);
        Kint::dump($wagers);
    }

}
