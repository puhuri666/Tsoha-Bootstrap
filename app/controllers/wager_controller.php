<?php

class WagerController extends BaseController {

    public static function find($id) {
        self::check_logged_in();
        $wagers = Wager::findByPerson($id);
        return $wagers;
    }

    public static function findByMatchup($id) {
        self::check_logged_in();
        $wagers = Wager::findByMatchup($id);
        return $wagers;
    }

    public static function create($id) {
        self::check_logged_in();
        $params = $_POST;
        $errors = array();

        if (!isset($params['choice'])) {
            $wager = new Wager(array(
                'bettor' => $_SESSION['user'],
                'matchup' => $id,
                'betting_amount' => $params['betting_amount']
            ));
            $errors = array_push($errors, 'Valitse tulos');
            Redirect::to('/vedonlyonti/' . $id, array('errors' => $errors, 'wager' => $wager));
            return;
        }
        
        $roundedBettingAmount = round($params['betting_amount'], 1);

        $wager = new Wager(array(
            'bettor' => $_SESSION['user'],
            'matchup' => $id,
            'betting_choice' => $params['choice'],
            'betting_amount' => $roundedBettingAmount   
        ));

        if (count($errors) > 0) {
            Redirect::to('/vedonlyonti/' . $id, array('errors' => $errors));
        }

        $errors = $wager->save();
        if (count($errors) === 0) {
            Redirect::to('/vedonlyonti/', array('message' => 'Veto lyöty onnistuneesti!'));
        } else {
            Redirect::to('/vedonlyonti/' . $id, array('errors' => $errors, 'wager' => $wager));
        }
    }

    public static function settleWagers($matchup_id, $betting_result) {
        self::check_logged_in();
        $wagersToSettle = Wager::findByMatchup($matchup_id);
        $wonWagers = array();
        $lostWagers = array();
        
        // add wager results to db
        
        Wager::updateResults($matchup_id, $betting_result);
        
        // find won wagers
        
        foreach ($wagersToSettle as $wager) {
            if (strcmp($betting_result, $wager->betting_choice) == 0) {
                array_push($wonWagers, $wager);
            }
        }
        
        // find lost wagers
        
        foreach ($wagersToSettle as $wager) {
            if (strcmp($betting_result, $wager->betting_choice) != 0) {
                array_push($lostWagers, $wager);
            }   
        }
        
        // adding result to lost wagers
        
        foreach ($lostWagers as $wager) {
            Wager::updateReturn($wager->id, 0);
        }

        // return winnings for each won wager
        
        foreach ($wonWagers as $wager) {
            $amountWon = $wager->betting_amount * $wager->betting_odds;
            Wager::updateReturn($wager->id, $amountWon);
            User::settleBet($wager->bettor, $amountWon);
        }
    }
    
    
}
