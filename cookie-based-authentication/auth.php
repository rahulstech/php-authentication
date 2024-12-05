<?php 
const USERS = [
    "root" => [
        "username" => "root",
        "password" => "root",
        "name" => "User 1"
    ],

    "foo" => [
        "username" => "foo",
        "password" => "bar",
        "name" => "User 2"
    ],

    "admin" => [
        "username" => "admin",
        "password" => "admin",
        "name" => "Admin User"
    ]
];

function create_auth_token($user) {
    return base64_encode(json_encode([
        'version' => 1,
        'data' => $user['username']
    ]));
}

function parse_auth_token($token) {
    return json_decode( base64_decode($token)
        , true // true => as associative array, false (default) => as object
    );
}

function get_auth_token_data($token) {
    $parsed_token = parse_auth_token($token);
    if (!is_null($parsed_token) && is_array($parsed_token)) {
        return $parsed_token['data'];
    }
    return null;
}

function authenticate_user($username, $password) {
    if ($username && isset(USERS[$username])) {
        $user = USERS[$username];
        if ($password === $user['password']) {
            return [
                'code' => 200,
                'message' => 'successful',
                'data' => $user
            ];
        }
        return [
            'code' => 401,
            'message' => 'password does not match'
        ];
    }
    return [
        'code' => 401,
        'message' => 'username not found'
    ];
}

function login() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!is_null($_POST) 
        && isset($_POST['username']) && isset($_POST['password'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $res = authenticate_user($username, $password);
            if ($res['code'] == 200) {
                $user = $res['data'];
                $token = create_auth_token($user);
                
                // set auth token in cookie. 
                // NOTE: this step must be performed before sending any headers and response

                setcookie('auth', // cookie name
                $token, // cookie value 
                time() + (86400 * 7), // cookie expire time, time() = current time in millis
                false, // secure, default false
                false // http only, default false
                );

                header('Location: ./'); // redirect to profile home
            }
            return $res;
        }
    }
    return null;
}

function logout() {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['logout'])) {
        setcookie('auth', // cookie name
            '', // cookie value 
            time() - 3600, // set older time from current to expire
            false, // secure, default false
            false // http only, default false
            );

        header('Location: ./'); // redirect to guest home
    }
}

function isLoggedIn() {
    if (isset($_COOKIE['auth'])) {
        $token = $_COOKIE['auth'];
        $username = get_auth_token_data($token);
        if (!is_null($username) && isset(USERS[$username])) {
            return USERS[$username];
        }
    }
    return null;
}
?>