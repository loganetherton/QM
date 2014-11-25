<?php

$connection = new AMQPConnection();
$connection->setLogin("guest");
$connection->setPassword("guest");
$connection->connect();

$channel = new AMQPChannel($connection);

$exchange = new AMQPExchange($channel);
$exchange->setName('exchange1');
$exchange->setType("fanout");
$exchange->declare();

/* create a queue object */
$queue = new AMQPQueue($channel);
$queue->setName('yelp');
$queue->declare();
$queue->bind('exchange1', 'routing.key');

$options = array(
    'yelpBusinessName'     => 'borough-market',
    'yelpBusinessLocation' => 'london-2',
    'timestamp'            => strtotime("-1 month")
);
$exchange->publish(serialize($options), 'routing.key');

$connection->disconnect();
