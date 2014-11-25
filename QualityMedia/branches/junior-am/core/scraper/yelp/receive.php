<?php
require __DIR__ . '/Yelp.php';
require __DIR__ . '/SearchParameters.php';
require __DIR__ . '/Adapters/FileAdapter.php';

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

$adapter = new FileAdapter('result.log');

function processMessage($envelope, $queue) {
    global $adapter;

    $deliveryTag = $envelope->getDeliveryTag();
    $options = unserialize($envelope->getBody());

    $timestamp = $options['timestamp'];
    $yelpParams = new SearchParameters();
    $yelpParams->setParameters((object) array (
        'yelpBusinessName'     => $options['yelpBusinessName'],
        'yelpBusinessLocation' => $options['yelpBusinessLocation']
    ));
    $yelpParams->sortByDate();

    $yelp = new Yelp($adapter);
    $yelp->scrape($yelpParams, $timestamp);

    $queue->ack( $deliveryTag );

    echo "Task Done\n";

    unset($yelp);
    unset($yelpParams);
    unset($options);
}

$queue->consume("processMessage");
$connection->disconnect();
