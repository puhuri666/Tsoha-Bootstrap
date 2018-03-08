<?php

class Team extends BaseModel {

    public $id, $name, $sport, $league;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM team');
        $query->execute();
        $rows = $query->fetchAll();

        foreach ($rows as $row) {
            $teams[] = new Team(array(
                'id' => $row['id'],
                'name' => $row['name'],
                'sport' => $row['sport'],
                'league' => $row['league']
            ));
        }
        return $teams;
    }
    
    public static function getLeague($id) {
        $query = DB::connection()->prepare('SELECT league FROM team WHERE id = :id');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        $sport = $row['league'];
        return $sport;
    }


}
