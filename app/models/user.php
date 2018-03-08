<?php

class User extends BaseModel {
    public $id, $firstname, $lastname, $address, $zipcode, $town, $email, $password, $balance, $superuser;
    
    public function __construct($attributes){
        parent::__construct($attributes);
    }
    
    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM bettor');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();
        
        foreach($rows as $row){
          $users[] = new User(array(
            'id' => $row['id'],
            'firstname' => $row['firstname'],
            'lastname' => $row['lastname'],
            'address' => $row['address'],
            'zipcode' => $row['zipcode'],
            'town' => $row['town'],
            'email' => $row['email'],
            'password' => $row['password'],
            'balance' => $row['balance'],
            'superuser' => $row['superuser']
          ));
        }
        return $users;
    }
    
    
    public static function find($id) {
        $query = DB::connection()->prepare('SELECT * FROM bettor WHERE id = :id LIMIT 1');
        $query->execute(array('id' => $id));
        $row = $query->fetch();
        
        if ($row) {
            $user = new User(array(
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'town' => $row['town'],
                'email' => $row['email'],
                'password' => $row['password'],
                'balance' => $row['balance'],
                'superuser' => $row['superuser']
            ));
            return $user;
        }
        return null;
    }
    
    public function save() {
        $query = DB::connection()->prepare('INSERT INTO bettor(firstname, lastname, address, zipcode, town, email, password) VALUES(:firstname, :lastname, :address, :zipcode, :town, :email, :password) RETURNING id');
        $query->execute(array('firstname' => $this->firstname, 'lastname' => $this->lastname, 'address' => $this->address, 'zipcode' => $this->zipcode, 'town' => $this->town, 'email' => $this->email, 'password' => $this->password));
        $row = $query->fetch();
        $this->id = $row['id'];
    }
    
    public static function authenticate() {
        $params = $_POST;
        $query = DB::connection()->prepare('SELECT * FROM bettor WHERE email = :email AND password = :password LIMIT 1');
        $query->execute(array('email' => $params['email'], 'password' => $params['password']));
        $row = $query->fetch();
        if ($row) {
            $user = new User(array(
               'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'town' => $row['town'],
                'email' => $row['email'],
                'password' => $row['password'],
                'balance' => $row['balance'],
                'superuser' => $row['superuser']
            ));
            return $user;
        } else {
            return null;
        }
        
    }
    
    
}

