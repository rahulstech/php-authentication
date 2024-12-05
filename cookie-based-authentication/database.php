<?php 

class User {

    private $username;

    private $password;

    private $name;

    public function __construct($username = null, $password = null, $name = null) {
        $this->username = $username;
        $this->password = $password;
        $this->name = $name;
    }

    public function __set($name, $value) {
        switch ($name) {
            case 'username': {
                $this->username = $value;
            }
            break;
            case 'password': {
                $this->password = $value;
            }
            break;
            case 'name': {
                $this->name = $value;
            }
        }
    }

    public function __get($name) {
        switch ($name) {
            case 'username': {
                return $this->username;
            }
            case 'password': {
                return $this->password;
            }
            case 'name': {
                return $this->name;
            }
        }
        return null;
    }

    public function __toString() {
        return "username = ". $this->username .", password = ". $this->password .", name = " . $this->name;
    }

    public function toArray() {
        return [
            'username' => $this->username,
            'password' => $this->password,
            'name' => $this->name
        ];
    }

    public static function fromArray($array) {
        $username = $array['username'];
        $password = $array['password'];
        $name = $array['name'];
        return new User($username, $password, $name);
    }
}

interface Database {

    public function connect();

    public function disconnect();

    public function getUserByUsername($user);

    public function saveUser($user);
}

class JsonFileBasedDatabaseImpl implements Database {

    private $filepath = null;

    private $users = [];

    public function __construct($filepath) {
        $this->filepath = $filepath;
    }

    public function __destruct() {
        $this->disconnect();
    }

    public function connect() {
        $path = $this->filepath;
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            if (strlen($content) > 0) {
                $data = json_decode($content, true);
                if (!is_null($data)) {
                    $this->users = $data;
                }
            }
        }
    }

    public function disconnect() {
        $users = $this->users;
        if (count($users) == 0) {
            return;
        }
        $content = json_encode($users);
        file_put_contents($this->filepath, $content);
    }

    public function getUserByUsername($username) {
        $users = $this->users;
        if ($username && array_key_exists($username, $users)) {
            $user = $users[$username];
            return User::fromArray($user);
        }
        return null;
    }

    public function saveUser($user) {
        if (!is_null($user)) {
            $this->users[$user->username] = $user->toArray();
        }
    }
}

function connect_database() {
    $db = new JsonFileBasedDatabaseImpl("/www/cookie-based-authentication/users.json");
    $db->connect();
    return $db;
}

?>