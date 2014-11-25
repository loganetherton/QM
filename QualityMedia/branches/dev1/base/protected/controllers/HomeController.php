<?php
/**
 * Default application controller.
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class HomeController extends Controller
{
    /**
     * @var string the name of the default action.
     */
    public $defaultAction = 'view';

    /**
     * @var string $layout the default layout for the controller view.
     */
    public $layout = false;

    /**
     * View action.
     */
    public function actionView()
    {
        $this->render('view');
    }

    /**
     * Splash page
.     */
    public function actionSplash()
    {
        $this->render('splash');
    }

    /**
     * Tropic Inc page.
     */
    public function actionTropic()
    {
        $this->render('tropic');
    }

    /**
     * Westcoast Tires & Services page.
     */
    public function actionWestcoast()
    {
        $this->render('westcoast');
    }

    /**
     * Products page.
     */
    public function actionProducts()
    {
        $this->render('products');
    }

    /**
     * Breast Cancer Awareness Month page.
     */
    public function actionbreastCancerAwarenessMonth()
    {
        $this->render('breastCancerAwarenessMonth');
    }

    /**
     * Yelp Management Landing Page.
     */
    public function actionYelpManagement()
    {
        $this->render('yelpManagement');
    }

    /**
     * Yelp Management Landing Page 2.
     */
    public function actionYelpManagement2()
    {
        $this->render('yelpManagement2');
    }
}