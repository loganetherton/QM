<?php
/**
 * This widget is a wrapper for recurly.js
 *
 * @author Jakub Pospiech <jakub@qualitymedia.com>
 */
class RecurlyWidget extends CWidget
{
    /**
     * @var array $config recurly.config values.
     */
    public $config = array();

    /**
     * @var array $subscriptionForm recurly.buildSubscriptionForm values.
     */
    public $subscriptionForm = array();

    /**
     * @var string $recurlyScript recurly js script key
     */
    public $recurlyScript = 'recurly.min.js';

    /**
     * @var object $recurly Recurly instance.
     */
    protected $recurly;

    /**
     * Initializes recurly widget.
     */
    public function init()
    {
        parent::init();

        $this->setRecurlyAlias();

        $this->registerRecurlyJs();
        $this->registerCoreScript();
    }

    /**
     * Register recurly path alias.
     */
    protected function setRecurlyAlias()
    {
        if(Yii::getPathOfAlias('recurly') === false) {
            Yii::setPathOfAlias('recurly', realpath(dirname(__FILE__)));
        }
    }

    /**
     * Include recurly.js file.
     */
    public function registerRecurlyJs()
    {
        $assets = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('recurly.lib.recurlyjs'));

        $cs = Yii::app()->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($assets.'/'.$this->recurlyScript, CClientScript::POS_HEAD);

        unset($cs);
    }

    /**
     * Registers necessary client scripts.
     */
    public function registerCoreScript()
    {
        $cs = Yii::app()->getClientScript();

        if(!empty($this->config)) {
            $options = CJavaScript::encode($this->config);
            $cs->registerScript(__CLASS__.'-config',"Recurly.config($options);");
        }

        $subscriptionForm = array_merge(
            array(
                'signature'=>Recurly_js::sign(array()),
                'currency'=>'USD',
            ),
            $this->subscriptionForm
        );

        if(!empty($subscriptionForm)) {
            $options = CJavaScript::encode($subscriptionForm);
            $cs->registerScript(__CLASS__.'-subscriptionForm',"Recurly.buildSubscriptionForm($options);");
        }

        unset($cs);
    }
}