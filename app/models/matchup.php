<?php

class Matchup extends BaseModel {

    public $id, $hometeam, $awayteam, $homeodds, $awayodds, $drawodds, $startdate, $result, 
            $scorehome, $scoreaway, $betting_result, $homeleague, $homesport, $awayleague, $awaysport;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    
    public static function all() {
        $query = DB::connection()->prepare
                ('SELECT m.id AS matchup, m.startdate AS startdate, home_team.name AS hometeam, co.hometeam AS homeodds, away_team.name AS awayteam, 
                        co.awayteam AS awayodds, co.draw AS drawodds, m.betting_result AS result, m.scorehome as scorehome, m.scoreaway as scoreaway,
                        home_team.league AS homeleague, home_team.sport AS homesport, away_team.league AS awayleague, away_team.sport AS awaysport
                        FROM matchup m
                        INNER JOIN team home_team
                        ON m.hometeam = home_team.id
                        INNER JOIN team away_team
                        ON m.awayteam = away_team.id
                        INNER JOIN current_odds co
                        ON m.id = co.matchup');
        $query->execute();
        $rows = $query->fetchAll();
        
        $matchups = array();
        
        foreach ($rows as $row) {
            $matchup = new Matchup(array(
                'id' => $row['matchup'],
                'hometeam' => $row['hometeam'],
                'awayteam' => $row['awayteam'],
                'homeodds' => $row['homeodds'],
                'awayodds' => $row['awayodds'],
                'drawodds' => $row['drawodds'],
                'startdate' => $row['startdate'],
                'result' => $row['result'],
                'scorehome' => $row['scorehome'],
                'scoreway' => $row['scoreaway'],
                'homeleague' => $row['homeleague'],
                'homesport' => $row['homesport'],
                'awayleague' => $row['awayleague'],
                'awaysport' => $row['awaysport']
            ));
            array_push($matchups, $matchup);
        }
        return $matchups;
    }

    public static function find($id) {
        $query = DB::connection()->prepare
                ('SELECT m.id AS matchup, m.startdate AS startdate, home_team.name AS hometeam, co.hometeam AS homeodds, 
                    away_team.name AS awayteam, co.awayteam AS awayodds, co.draw AS drawodds, m.scorehome as scorehome, m.scoreaway as scoreaway, m.betting_result AS result
                    FROM matchup m
                    INNER JOIN team home_team
                    ON m.hometeam = home_team.id
                    INNER JOIN team away_team
                    ON m.awayteam = away_team.id
                    INNER JOIN current_odds co
                    ON m.id = co.matchup
                    WHERE m.id = :id
                    LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();

        if ($row) {
            $matchup = new Matchup(array(
                'id' => $row['matchup'],
                'hometeam' => $row['hometeam'],
                'awayteam' => $row['awayteam'],
                'homeodds' => $row['homeodds'],
                'awayodds' => $row['awayodds'],
                'drawodds' => $row['drawodds'],
                'startdate' => $row['startdate'],
                'scorehome' => $row['scorehome'],
                'scoreaway' => $row['scoreaway'],
                'betting_result' => $row['result']
            ));
            return $matchup;
        }
        return null;
    }

    
    public static function getTeamNames($id) {
        $matchup = Matchup::find($id);
        $hometeam = $matchup->hometeam;
        $awayteam = $matchup->awayteam;
        $teamNames = array($hometeam,$awayteam);
        return $teamNames;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO matchup(startdate, scorehome, scoreaway, betting_result, hometeam, awayteam) VALUES(:startdate, :scorehome, :scoreaway, :betting_result, :hometeam, :awayteam) RETURNING id');
        $query->execute(array('startdate' => $this->startdate, 'scorehome' => $this->scorehome, 'scoreaway' => $this->scoreaway, 'betting_result' => $this->betting_result, 'hometeam' => $this->hometeam, 'awayteam' => $this->awayteam));
        $row = $query->fetch();
        $this->id = $row['id'];

        $oddsQuery = DB::connection()->prepare('INSERT INTO current_odds(matchup, hometeam, draw, awayteam) VALUES(:matchup, :homeodds, :drawodds, :awayodds)');
        $oddsQuery->execute(array('matchup' => $this->id, 'homeodds' => $this->homeodds, 'drawodds' => $this->drawodds, 'awayodds' => $this->awayodds));
    }
    
    public function destroy() {
        $firstQuery = DB::connection()->prepare('DELETE FROM current_odds WHERE matchup = :id');
        $firstQuery->execute(array('id' => $this->id));
        
        $secondQuery = DB::connection()->prepare('DELETE FROM matchup WHERE id = :id');
        $secondQuery->execute(array('id' => $this->id));
    }
    
    public function validateTeams() {
        $errors = array();
        if (strcmp($this->hometeam, $this->awayteam) == 0) {
            array_push($errors, 'Valitse eri joukkueet');
        }
        return $errors;
    }
    
    public function validateOddsInput() {
        $errors = array();

        if (strcmp($this->homeleague, $this->awayleague) != 0) {
            array_push($errors, 'Valitse joukkueet samasta sarjasta');
        }
        
        $odds = new Odds(array(
            'id' => $this->id,
            'homeodds' => $this->homeodds,
            'drawodds' => $this->drawodds,
            'awayodds' => $this->awayodds
        ));
        
        if (!$odds->validate()) {
            array_push($errors, 'Anna kelvolliset kertoimet');
        }
        
        $date = explode('-', $this->startdate);
        if (count($date) > 2) {
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];
            if (!ctype_digit($year) || strlen((string) $year) != 4 || !ctype_digit($month) || strlen((string) $month) != 2 || !ctype_digit($day) || strlen((string) $day) != 2) {
                array_push($errors, 'Anna kelvollinen päivämäärä');
            }
        } else {
            array_push($errors, 'Anna kelvollinen päivämäärä');
        }
        return $errors;
    }
    
    

}
