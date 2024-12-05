<?php 
require_once('database.php');

class Authenticator {

    private $db = null;

    private $user = null;

    public function __construct() {
        $this->db = connect_database();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!is_null($_POST) 
            && isset($_POST['username']) && isset($_POST['password'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $res = $this->authenticate_user($username, $password);
                if ($res['code'] == 200) {
                    $user = $res['data'];
                    $token = $this->create_auth_token($user);
                    
                    // set auth token in cookie. 
                    // NOTE: this step must be performed before sending any headers and response
                    $this->setCookie($token, 7);
                    $this->user = $user;

                    return [
                        'code' => 200,
                        'message' => 'successful'
                    ];
                }
                return $res;
            }
        }
        return null;
    }

    public function getLoggedUser() {
        return $this->user;
    }

    public function logout() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
            $this->removeCookie();
            $this->user = null;
            return true; // redirect to guest home
        }
        return false;
    }

    public function isLoggedIn() {
        if (isset($_COOKIE['auth'])) {
            $token = $_COOKIE['auth'];
            $username = $this->get_auth_token_data($token);
            $user = $this->db->getUserByUsername($username);
            if (!is_null($user)) {
                $this->user = $user;
                return true;
            }
        }
        return false;
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!is_null($_POST) &&
            isset($_POST['username']) && isset($_POST['password'])
            && isset($_POST['confirm_password']) && isset($_POST['name'])) {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $confirm_password = $_POST['confirm_password'];
                $name = $_POST['name'];
                if (!$username) {
                    return [
                        'code' => 400,
                        'message' => 'username not provided'
                    ];
                }
                if ($password !== $confirm_password) {
                    return [
                        'code' => 400,
                        'message' => 'password and confirm password does not match'
                    ];
                }
                if (!$name) {
                    return [
                        'code' => 400,
                        'message' => 'name not provided'
                    ];
                }
                $olduser = $this->db->getUserByUsername($username);
                if (!is_null($olduser)) {
                    return [
                        'code' => 400,
                        'message' => "username $username exists"
                    ];
                }
                $user = new User($username, $password, $name);
                $this->db->saveUser($user);

                $token = $this->create_auth_token($user);
                $this->setCookie($token, 7);
                $this->user = $user;

                return [
                    'code' => 200,
                    'message' => 'successful'
                ];
            }
        }
    }

    private function create_auth_token($user) {
        return base64_encode(json_encode([
            'version' => 1,
            'data' => $user->username
        ]));
    }

    private function parse_auth_token($token) {
        return json_decode( base64_decode($token)
            , true // true => as associative array, false (default) => as object
        );
    }

    private function get_auth_token_data($token) {
        $parsed_token = $this->parse_auth_token($token);
        if (!is_null($parsed_token) && is_array($parsed_token)) {
            return $parsed_token['data'];
        }
        return null;
    }

    private function authenticate_user($username, $password) {
        $user = $this->db->getUserByUsername($username);
        if (!is_null($user) && $password === $user->password) {
            return [
                'code' => 200,
                'message' => 'successful',
                'data' => $user
            ]; 
        }
        return [
            'code' => 401,
            'message' => 'username and/password does not match'
        ];
    }

    private function setCookie($data, $expire_days) {
        setcookie('auth', // cookie name
            $data, // cookie value 
            time() + (86400 * $expire_days), // cookie expire time, time() = current time in millis
            false, // secure, default false
            false // http only, default false
            );
    }

    private function removeCookie() {
        $this->setCookie('', -1);
    }
}

return new Authenticator();

?>