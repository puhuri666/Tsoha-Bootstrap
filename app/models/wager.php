<?php

class Wager extends BaseModel {

    public $bettor, $matchup, $betting_choice, $betting_amount, $betting_odds, $hometeam, $awayteam, $settled;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function save() {
        //current_odds
        
        $errors = array();
        
        if (!is_numeric($this->betting_amount)) {
            array_push($errors, 'Anna luku panokseksi');
            return $errors;
        }
        
        if ($this->betting_amount < 0.01) {
            array_push($errors, 'Anna nollaa suurempi luku panokseksi');
            return $errors;
        } 
        
        $oddsQuery = DB::connection()->prepare('SELECT * FROM current_odds WHERE matchup = :matchup');
        $oddsQuery->execute(array('matchup' => $this->matchup));
        $currentOdds = $oddsQuery->fetch();
                
        if (strcmp($this->betting_choice, '1') === 0) {
            $this->betting_odds = $currentOdds['hometeam'];
        } else if (strcmp($this->betting_choice, 'X') === 0) {
            $this->betting_odds = $currentOdds['draw'];
        } else if (strcmp($this->betting_choice, '2') === 0) {
            $this->betting_odds = $currentOdds['awayteam'];
        }
        $errors = User::reduceBalance($this->bettor, $this->betting_amount);
        // save
        if (count($errors) === 0) {
            $query = DB::connection()->prepare('INSERT INTO wager(bettor, matchup, betting_choice, betting_amount, betting_odds) '
                    . 'VALUES(:bettor, :matchup, :betting_choice, :betting_amount, :betting_odds)');
            $query->execute(array('bettor' => $this->bettor, 'matchup' => $this->matchup, 'betting_choice' => $this->betting_choice, 'betting_amount' =>
                $this->betting_amount, 'betting_odds' => $this->betting_odds));
        }
        return $errors;
    }

    public static function findByPerson($id) {
        $wagers = array();
        $query = DB::connection()->prepare('SELECT * FROM wager WHERE bettor = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();

        if ($rows) {
            foreach ($rows as $row) {
                $teamNames = Matchup::getTeamNames($row['matchup']);
                $wager = new Wager(array(
                    'bettor' => $row['bettor'],
                    'matchup' => $row['matchup'],
                    'betting_choice' => $row['betting_choice'],
                    'betting_amount' => $row['betting_amount'],
                    'betting_odds' => $row['betting_odds'],
                    'hometeam' => $teamNames[0],
                    'awayteam' => $teamNames[1],
                    'settled' => $row['settled']
                ));
                array_push($wagers, $wager);
            }
            return $wagers;
        }
        return null;
    }
    
    public static function findByMatchup($id) {
        $wagers = array();
        $query = DB::connection()->prepare('SELECT * FROM wager WHERE matchup = :id');
        $query->execute(array('id' => $id));
        $rows = $query->fetchAll();
        if ($rows) {
            foreach ($rows as $row) {
                $teamNames = Matchup::getTeamNames($id);
                $wager = new Wager(array(
                    'bettor' => $row['bettor'],
                    'matchup' => $row['matchup'],
                    'betting_choice' => $row['betting_choice'],
                    'betting_amount' => $row['betting_amount'],
                    'betting_odds' => $row['betting_odds'],
                    'hometeam' => $teamNames[0],
                    'awayteam' => $teamNames[1],
                    'settled' => $row['settled']
                ));
                array_push($wagers, $wager);
            }
            return $wagers;
        }
        return null;
    }
    
    public static function updateResults($id, $betting_result) {
        $query = DB::connection()->prepare('UPDATE wager SET result = :result, settled = true WHERE matchup = :id');
        $query->execute(array('result' => $betting_result, 'id' => $id));
    }

}
