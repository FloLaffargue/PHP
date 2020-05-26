<?php
session_start();
session_destroy();
header('Location:' . $router->url('login'));
exit();