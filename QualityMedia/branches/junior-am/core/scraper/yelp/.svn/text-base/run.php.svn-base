<?php

require __DIR__ . '/Yelp.php';
require __DIR__ . '/SearchParameters.php';

$yelpParams = new SearchParameters();
$yelpParams->setParameters((object) array (
    'yelpBusinessName'     => 'borough-market',
    'yelpBusinessLocation' => 'london-2'
));

$yelp = new Yelp();
$yelp->scrape ($yelpParams, strtotime("-1 month"));

var_dump($yelp->getReviews());
