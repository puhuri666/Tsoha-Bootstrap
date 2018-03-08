<?php

class WagerController extends BaseController {
    
    public static function find($id) {
        $wagers = Wager::findByPerson($id);
        return $wagers;
    }
    
    public static function findByMatchup($id) {
        $wagers = Wager::findByMatchup($id);
        return $wagers;
    }
    
    public static function create($id) {
        $params = $_POST;
        $errors = array();
        
        if (!isset($params['choice'])) {
            $wager = new Wager(array(
               'bettor' => $_SESSION['user'],
                'matchup' => $id,
                'betting_amount' => $params['betting_amount']
            ));
            $errors = array_merge($errors, 'Valitse tulos');
            Redirect::to('/vedonlyonti/'. $id, array('errors' => $errors, 'wager' => $wager));
            return;
        }
        
        $wager  = new Wager(array(
            'bettor' => $_SESSION['user'],
            'matchup' => $id,
            'betting_choice' => $params['choice'],
            'betting_amount' => $params['betting_amount']
        ));

        if (count($errors) > 0) {
          Redirect::to('/vedonlyonti/' . $id, array('errors' => $errors));  
        }
        
        $errors = $wager->save();
        if (count($errors) === 0) {
           Redirect::to('/vedonlyonti/', array('message' => 'Veto lyöty onnistuneesti!')); 
        } else {
            Redirect::to('/vedonlyonti/'. $id, array('errors' => $errors, 'wager' => $wager));
        }
    }
    
}