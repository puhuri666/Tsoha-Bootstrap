<?php

class BettingController extends BaseController {
    
    public static function index() {
        $user = BaseController::get_user_logged_in();
        $matchups = Matchup::all();
        View::make('betting/index.html', array('matchups' => $matchups, 'user' => $user));
    }
    
    public static function show($id) {
        $user = BaseController::get_user_logged_in();
        $matchup = Matchup::find($id);
        $wagers = WagerController::findByMatchup($id);
        View::make('betting/inspect.html', array('matchup' => $matchup, 'user' => $user, 'wagers' => $wagers));
    }
    
    public static function add() {
        $teams = Team::all();
        View::make('betting/new.html', array('teams' => $teams));
    }
    
    public static function createMatchup() {
        $params = $_POST;
        $errors = array();
        $matchup = new Matchup(array(
            'startdate' => $params['startdate'],
            'scorehome' => 0,
            'scoreaway' => 0,
            'betting_result' => 'Ei tulosta',
            'hometeam' => $params['hometeam'],
            'awayteam' => $params['awayteam'],
            'homeodds' => $params['homeodds'],
            'drawodds' => $params['drawodds'],
            'awayodds' => $params['awayodds'],
            'homeleague' => Team::getLeague($params['hometeam']),
            'awayleague' => Team::getLeague($params['awayteam'])
        ));
        $errors = array_merge($errors, $matchup->validateOddsInput());
        $errors = array_merge($errors, $matchup->validateTeams());
        if (count($errors) == 0) {
            $matchup->save();
            Redirect::to('/vedonlyonti/' . $matchup->id, array('message' => 'Vedonlyöntikohde lisätty!'));
        } else {
            $teams = Team::all();
            Redirect::to('/vedonlyonti/lisaa', array('errors' => $errors, 'teams' => $teams, 'matchup' => $matchup));
        }  
    }
    
    public static function deleteMatchup($id) {
        $matchup = new Matchup(array('id' => $id));
        $matchup->destroy();
        Redirect::to('/vedonlyonti', array('message' => 'Vedonlyöntikohde poistettu'));
    }
    
    public static function updateOdds($id) {
        $params = $_POST;
        $attributes = array (
            'id' => $id,
            'homeodds' => $params['homeodds'],
            'drawodds' => $params['drawodds'],
            'awayodds' => $params['awayodds'] 
        );
        $odds = new Odds($attributes);
        if ($odds->validate()) {
            $odds->update();
            Redirect::to('/vedonlyonti/' . $odds->id, array('message' => 'Kerrointa on muokattu onnistuneesti!'));
        } else {
            Redirect::to('/vedonlyonti/' . $odds->id, array('message' => 'Kerroinasettelussa virhe'));
        }      
    }
    
    public static function updateScore($id) {
        $params = $_POST;
        $betting_result = '';
        if ($params['scorehome'] > $params['scoreaway']) {
            $betting_result = '1';
        } else if ($params['scoreaway'] < $params['scoreaway']) {
            $betting_result = '2';
        } else {
            $betting_result = 'X';
        }
        
        $attributes = array (
            'id' => $id,
            'scorehome' => $params['scorehome'],
            'scoreaway' => $params['scoreaway'],
            'betting_result' => $betting_result
        );
        $score = new Score($attributes);
        $errors = $score->validate();
        if (count($errors) == 0) {
            $score->update();
            Redirect::to('/vedonlyonti/' . $score->id, array('message' => 'Tulosta muokattu onnistuneesti!'));
        } else {
            Redirect::to('/vedonlyonti/' . $score->id, array('errors' => $errors));
        }
    }
    
    
    
}

