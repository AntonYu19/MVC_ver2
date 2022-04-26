<?php
require '../Base/init.php';
require '../vendor/autoload.php';

use \Base\Model\Factory as Factory;

$dotenv = \Dotenv\Dotenv::create(__DIR__ . '/../');
$dotenv->load();

$app = new \Base\Application($config);
$app->init();

$users = Factory::getList(Factory::MODEL_USER, __FILE__, 2, [], 'id ASC');

echo '<pre>';
var_dump($users);

$db = Base\Context::getInstance()->getDbConnection();
echo $db->getLog();