<?php

class Review
{
    public $publishedOnTimestamp;   // The timestamp of posting
    public $handledByCustomerServiceAgent; // The agent who handles the review (null if unknown)
    public $matchesSearchParameter; // The search parameter this review matches
    public $hasRating;              // an integer from 0 (a bad review) to 100 (a good review)
    public $hasMetadataObject;     // Any other metadata to save, array or object
    public $scrapedOnTimestamp;    // Timestamp scrapped 
    public $hasURL;                // URL that uniquely identifies this review (if we go to the url we see the review)
    public $publishedByAuthorURL;  // URL identifying the poster of the review (if avail.)
    // Either one of:
    public $hasPostContents;       // The review contents as a string, text-only
}
