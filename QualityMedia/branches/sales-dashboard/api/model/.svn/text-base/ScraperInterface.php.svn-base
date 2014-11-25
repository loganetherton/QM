<?php

interface User {
  // Any methods required for login should go here...
}

//////////////
// AuthData //
//////////////

// Credentials to a website
interface AuthData {
}

class YelpAPIAuthData implements AuthData {
  // Yelp API authentication data to be stored here
  private $privateApiKey;
  private $publicApiKey;
}

class YelpAuthData implements AuthData {
  // Yelp authentication data to be stored here
  private $email;
  private $password;
}

class TwitterAuthData implements AuthData {
  // Twitter authentication data to be stored here
  private $username;
  private $password;
}

// For when no authentication data is required.
class NoAuthData implements AuthData {
}

class ClientBranch {
  public $belongsToClient;
}

////////////
// Review //
////////////

class Review {
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

///////////////////////
// Search parameters //
///////////////////////

/**
 * SearchParameters objects (e.g. YelpSearchParameters) are defined as 
 * individual classes because they are representable in the DB and admin panels, 
 * and they should be accessible through the API.
 */

interface SearchParameters {

  public function setUseAuthData (AuthData $auth);
  public function getUseAuthData ();


  public function setParameters($objParams);
  public function getParameters(); // @return array

  // Disregard these 2 methods for now
  public function setDefinedByUser (User $user);
  public function getDefinedByUser (); // @return User

  // Disregard these 2 methods for now
  public function setRelevantToClientBranch (ClientBranch $branch);
  public function getRelevantToClientBranch ();

}

class YelpSearchParameters implements SearchParameters {
  private $relevantToClientBranch;
  private $useAuthData;
  private $definedByUser;

  private $yelpBusinessName;
  private $yelpBusinessLocation;

  // AuthData may be required by some scraping locations, but not by yelp...
  public function setUseAuthData (AuthData $auth) { /* do nothing */ }
  public function getUseAuthData () { return new NoAuthData(); }

  public function setParameters($obj) {
    $this->yelpBusinessName     = $obj->yelpBusinessName;
    $this->yelpBusinessLocation = $obj->yelpBusinessLocation;
  }
  public function getParameters () {
    $o = new stdClass(); 
    $o->yelpBusinessName     = $this->yelpBusinessName;
    $o->yelpBusinessLocation = $this->yelpBusinessLocation;
    return $o;
  }

  // Disregard these 2 methods for now
  public function setRelevantToClientBranch (ClientBranch $branch) {}
  public function getRelevantToClientBranch () {}

  // Disregard these 2 methods for now
  public function setDefinedByUser (User $user) {}
  public function getDefinedByUser () {}

}

///////////////////////
// Scraper Interface //
///////////////////////

class ScrapeException extends Exception {
  public $userErrorMessageString = ''; // User-friendly error message that we can display to a non-technical user
  public $intTimeout; // What the timeout was, if timeout failure
  public $strError; // What the error given by the remove host was, if a curl error
}

interface SearchScraperInterface {

  // We input the specific SearchParameters to use.
  // Returns an array of objects of type Review.
  // The Reviews returned will all be dated $afterTimestamp (which is an integer).
  // Throws a ScrapeException on failure.
  public function scrape (SearchParameters $params, $afterTimestamp);
}

// Sample of what we need:

class YelpScraper implements SearchScraperInterface {
  public function scrape (SearchParameters $params, $afterTimestamp) {
    $review = new Review();
    return array ($review);
    // throw new ScrapeException();
  }
}

$yelp = new YelpSearchParameters();
$yelp->setParameters((object) array (
  'yelpBusinessName'     => 'gordon-ramsay'
, 'yelpBusinessLocation' => 'london'
));

// Should return last month's reviews from 
// http://www.yelp.com/biz/gordon-ramsay-london
$scraper = new YelpScraper();
var_dump ($scraper->scrape ($yelp, strtotime("-1 month"))); 

