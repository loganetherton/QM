<?php

require __DIR__ . '/ganon.php';
require __DIR__ . '/Review.php';
require __DIR__ . '/HttpQuery.php';

class Yelp
{
    private $reviews = array();
    private $pageLimit = 40;

    public function __construct($saveAdapter = null)
    {
        $this->saveAdapter = $saveAdapter;
    }

    public function scrape(SearchParameters $params, $afterTimestamp)
    {
        $httpQuery = new HttpQuery($params);
        $body = $httpQuery->useProxy()->query();
        if (!$body) {
            return false;
        }

        $html = str_get_dom($body);
        $reviews = $html('li[itemprop="review"]');
        $getNextPage = true;

        if (!$reviews) {
            return null;
        }

        foreach($reviews as $node) {
            $review = new Review;
            $pubishDate = strtotime( $node('meta[itemprop="datePublished"]', 0)->getAttribute('content') );

            if ($pubishDate >= $afterTimestamp) {
                $review->publishedOnTimestamp = $pubishDate;
                $review->scrapedOnTimestamp = time();
                $rating = floatVal($node('meta[itemprop="ratingValue"]', 0)->getAttribute('content'));
                $review->hasRating = (100 * $rating) / 5;
                $review->hasPostContents = trim($node('p[itemprop="description"]', 0)->getInnerText());
                $review->publishedByAuthorURL = trim($node('.user-passport a', 0)->getAttribute('href'));
                $review->hasURL = 'http://www.yelp.com' . trim($node('.i-orange-link-common-wrap', 0)->getAttribute('href'));

                if ($this->saveAdapter) {
                    $this->saveAdapter->save($review);
                } else {
                    $this->reviews[] = $review;
                }
            } else {
                $getNextPage = false;
                break;
            }
        }

        if ($getNextPage) {
            $pagination = $html('#paginationControls td > span', 0);

            $nextPage = intval($pagination->getInnerText());
            $startParam = $nextPage * $this->pageLimit;

            $params->sortByDate()
                ->setStart($startParam);
            return $this->scrape($params, $afterTimestamp);
        } else {
            return null;
        }
    }

    public function getReviews()
    {
        return $this->reviews;
    }
}
