<?php

const DB_HOST = 'localhost';
const DB_NAME = 'api';
const DB_USER = 'root';
const DB_PASS = 'localroot';

const LOGGING = true;

require_once('vendor/autoload.php');

new \Api\Controllers\FrontController();