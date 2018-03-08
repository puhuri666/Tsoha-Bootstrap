<?php

class Score extends BaseModel {
    
    public $id, $scorehome, $scoreaway, $betting_result;
    
    public function __construct($attributes) {
        parent::__construct($attributes);
    }
    
    public function validate() {
        $errors = array();
        if (!ctype_digit($this->scorehome) || !ctype_digit($this->scoreaway)) {
            array_push($errors, 'Anna kelvolliset kertoimet');
        }
        
        if ($this->scorehome > $this->scoreaway) {
            $this->betting_result = 1;
        } else if ($this->scoreaway > $this->scorehome) {
            $this->betting_result = 2;
        } else {
            $this->betting_result = 'X';
        }
        
        return $errors;
    }
    
    public function update() {
        $query = DB::connection()->prepare('UPDATE matchup SET scorehome = :scorehome,  scoreaway = :scoreaway, betting_result = :betting_result WHERE id = :id');
        $query->execute(array('scorehome' => $this->scorehome, 'scoreaway' => $this->scoreaway, 'betting_result' => $this->betting_result, 'id' => $this->id));
    }
    
}
