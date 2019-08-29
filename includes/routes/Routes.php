<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

Route::set('index.php', function () {
    MiddleWare::Authorize();
    IndexController::Home();
});

Route::set('users', function () {
    $request = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();
    if ($request == 'GET') {
        MiddleWare::Authorize();
        $controller->AllUsers();
    } else if ($request == 'POST') {
        $controller->NewUser();
    } else if ($request == 'PUT') {
        MiddleWare::Authorize();
        $controller->UpdateUserinfo();
    } else {
        Response::MethodNotAllowed();
    }
});

Route::set('login', function () {
    $request = $_SERVER['REQUEST_METHOD'];
    $controller = new UserController();
    if ($request == 'POST') {
        $controller->Login();
    } else {
        Response::MethodNotAllowed();
    }
});
