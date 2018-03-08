<?php

class Odds extends BaseModel {

    public $id, $homeodds, $drawodds, $awayodds;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public function update() {
        $query = DB::connection()->prepare('UPDATE current_odds SET hometeam = :homeodds, draw = :drawodds, awayteam = :awayodds WHERE matchup = :id');
        $query->execute(array('homeodds' => $this->homeodds, 'drawodds' => $this->drawodds, 'awayodds' => $this->awayodds, 'id' => $this->id));
    }

    public function validate() {
        $errors = array();
        if (!is_numeric($this->homeodds) || $this->homeodds < 1.01 || !is_numeric($this->drawodds) || $this->drawodds < 1.01 || !is_numeric($this->awayodds) || $this->awayodds < 1.01) {
            return false;
        }
        return true;
    }

}
