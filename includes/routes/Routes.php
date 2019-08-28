<?php

Route::set('index.php', function() {
    IndexController::Home();
});

Route::set('users', function() {
    UserController::AllUsers();
});

Route::set('login', function() {
    echo 'login page';
});
?>