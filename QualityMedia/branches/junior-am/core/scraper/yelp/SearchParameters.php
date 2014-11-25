<?php

class SearchParameters
{
    private $url = 'http://www.yelp.com/biz/';
    private $yelpBusinessName;
    private $yelpBusinessLocation;
    private $yelpBusinessStart = 0;
    private $yelpReviewSortByDate = false;

    public function setParameters($obj)
    {
        $this->yelpBusinessName = $obj->yelpBusinessName;
        $this->yelpBusinessLocation = $obj->yelpBusinessLocation;
    }

    public function getParameters ()
    {
        $o = new stdClass();
        $o->yelpBusinessName = $this->yelpBusinessName;
        $o->yelpBusinessLocation = $this->yelpBusinessLocation;
        return $o;
    }

    public function setStart($start)
    {
        $this->yelpBusinessStart = $start;
        return $this;
    }

    public function sortByDate()
    {
        $this->yelpReviewSortByDate = true;
        return $this;
    }

    public function generateUrl()
    {
        $queryString = $this->url;
        $queryString .= $this->yelpBusinessName;
        $queryString .= '-' . $this->yelpBusinessLocation;

        if ($this->yelpReviewSortByDate) {
            $queryString .= '?sort_by=date_desc';
        }

        if ($this->yelpBusinessStart > 0) {
            if ($this->yelpReviewSortByDate) {
                $queryString .= '&';
            } else {
                $queryString .= '?';
            }
            $queryString .= 'start=' . $this->yelpBusinessStart;
        }


        return $queryString;
    }
}
