<?php

define('ROOT_FOLDER', __DIR__);

spl_autoload_register(function ($class) {
    require ROOT_FOLDER . '/classes/' . $class . '.php';
});