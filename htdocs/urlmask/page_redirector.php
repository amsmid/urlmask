<?php // -*- encoding utf-8 -*-

require_once(__DIR__ . '/../../controllers/PageRedirectorController.php');

$conf_arr = parse_ini_file(__DIR__ . '/../../conf/urlmask.ini', true);
$env = '';
if(isset($conf_arr['environment']['env']) === true)
{
    $env = $conf_arr['environment']['env'];
}

$controller = new PageRedirectorController($env);
$controller->execute();

