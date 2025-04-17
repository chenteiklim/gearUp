<?php
require __DIR__ . '/vendor/autoload.php';

$options = [
    'cluster' => 'mt1',
    'useTLS' => true
];

$pusher = new Pusher\Pusher(
    '629cb6747695cbb8c93f',
    '1e6f7bc862cc5c7225c7',
    '1962376',
    $options
);
//include_once $_SERVER['DOCUMENT_ROOT'] . '/inti/gadgetShop/pusher.php';

?>
