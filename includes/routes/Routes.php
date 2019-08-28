<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

Route::set('index.php', function () {
    MiddleWare::Authorize();
    IndexController::Home();
});

Route::set('users', function () {
    MiddleWare::Authorize();
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        UserController::AllUsers();
    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        UserController::SaveUser();
    } else {
        http_response_code(405);
    }
});

Route::set('login', function () {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        UserController::Login();
    } else {
        http_response_code(405);
    }
});
