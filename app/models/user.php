<?php

class User extends BaseModel {

    public $id, $firstname, $lastname, $address, $zipcode, $town, $email, $password, $balance, $superuser;

    public function __construct($attributes) {
        parent::__construct($attributes);
    }

    public static function all() {
        $query = DB::connection()->prepare('SELECT * FROM bettor');
        $query->execute();
        $rows = $query->fetchAll();
        $users = array();

        foreach ($rows as $row) {
            $balance = round($row['balance'], 1);
            $users[] = new User(array(
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'town' => $row['town'],
                'email' => $row['email'],
                'password' => $row['password'],
                'balance' => $balance,
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
            $balance = round($row['balance'], 1);
            $user = new User(array(                
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'town' => $row['town'],
                'email' => $row['email'],
                'password' => $row['password'],
                'balance' => $balance,
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
    
    public function updateAddress() {
        $query = DB::connection()->prepare('UPDATE bettor SET address = :address, zipcode = :zipcode, town = :town WHERE id = :id');
        $query->execute(array('address' => $this->address, 'zipcode' => $this->zipcode, 'town' => $this->town, 'id' => $this->id));
    }

    public static function authenticate() {
        $params = $_POST;
        $query = DB::connection()->prepare('SELECT * FROM bettor WHERE email = :email AND password = :password LIMIT 1');
        $query->execute(array('email' => $params['email'], 'password' => $params['password']));
        $row = $query->fetch();
        if ($row) {
            $balance = round($row['balance'], 1);
            $user = new User(array(
                'id' => $row['id'],
                'firstname' => $row['firstname'],
                'lastname' => $row['lastname'],
                'address' => $row['address'],
                'zipcode' => $row['zipcode'],
                'town' => $row['town'],
                'email' => $row['email'],
                'password' => $row['password'],
                'balance' => $balance,
                'superuser' => $row['superuser']
            ));
            return $user;
        } else {
            return null;
        }
    }

    public static function reduceBalance($id, $betting_amount) {
        //get
        $currentBalance = User::getCurrentBalace($id);
        //set
        $errors = array();
        if ($currentBalance['balance'] - $betting_amount < 0) {
            array_push($errors, 'Ei tarpeeksi saldoa');
        } else {
            $newBalance = round($currentBalance['balance'] - $betting_amount, 1);
            $setQuery = DB::connection()->prepare('UPDATE bettor SET balance = :balance WHERE id = :id');
            $setQuery->execute(array('balance' => $newBalance, 'id' => $id));
        }
        return $errors;
    }

    public static function settleBet($id, $balanceToAdd) {
        $currentBalance = User::getCurrentBalace($id);
        
        $newBalance = round($currentBalance['balance'] + $balanceToAdd, 1);
        
        $setQuery = DB::connection()->prepare('UPDATE bettor SET balance = :balance WHERE id = :id');
        $setQuery->execute(array('balance' => $newBalance, 'id' => $id));
    }

    public static function getCurrentBalace($id) {
        $getQuery = DB::connection()->prepare('SELECT balance FROM bettor WHERE id = :id');
        $getQuery->execute(array('id' => $id));
        $currentBalance = $getQuery->fetch();
        return $currentBalance;
    }
    
    public function validateAddressInput() {
        $errors = array();
        if (!is_numeric($this->zipcode) || $this->zipcode < 0 || strlen((string)$this->zipcode) > 5) {
            array_push($errors, 'Anna kelvollinen postinumero');
        }
        if (!is_string($this->address) || strlen($this->address) > 150 || strlen($this->address) < 5) {
            array_push($errors, 'Anna kelvollinen katuosoite');
        }
        if (!is_string($this->address) || strlen($this->address) > 50 || strlen($this->address) < 2) {
            array_push($errors, 'Anna kelvollinen postitoimipaikka');
        }
        return $errors;
    }
    
    public function destroy() {
        $deleteWagers = DB::connection()->prepare('DELETE FROM wager WHERE bettor = :id');
        $deleteWagers->execute(array('id' => $this->id));
        
        $deleteUser = DB::connection()->prepare('DELETE FROM bettor WHERE id = :id');
        $deleteUser->execute(array('id' => $this->id));
    }
    
}
