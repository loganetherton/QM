<?php

class SiteController extends Controller
{
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		Yii::app()->clientScript->scriptMap['*.js'] = false;
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
			
			
		);	
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		//$this->render('index');

		if (Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->createUrl('site/login'));
		}else{
			$this->redirect(Yii::app()->createUrl('site/search'));
		}
		
		
	}

 public function actionFaq()
        {
                $this->render('faq');
                
        }
		 public function actionTerms()
        {
                $this->render('terms');
                
        }
		public function actionPrivacy()
        {
                $this->render('privacy');
                
        }
	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest){
				echo $error['message'];
				//$this->render('error', $error); 
			}else{
				$this->render('error', $error);
			}
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->createUrl('site/search')); 
				//$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}
	
	/**
	 * Displays the search page
	 */
	public function actionSearch()
	{
		if (Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->createUrl('site/login'));
		}	
		
		//echo CHtml::encode(print_r($_POST, true));


		$model=new SearchForm;
		$dispList = '';
		$checkval = '';
		$pages = '';
		$item_count = '';
		$page_size = '';
		//$displayList = '';
		$recList = array();
		$dataProvider = '';
		$postFlag = 0;
		
		if(isset($_POST['SearchForm']))
		{
			//print_r($_POST);
			$model->attributes=$_POST['SearchForm'];
			
			if($model->validate())
			{
				$search=$model->search;
				//$displayList=new SearchForm; 
				/*$zip=array('location'=>$model->zip);
				$result = Yii::app()->yelpsrch->search($zip);*/
				//Yii::app()->session->destroy();
				unset(Yii::app()->session['searchList']);
				Yii::app()->session['searchList'] = '';
				unset(Yii::app()->session['itemcnt']);
				Yii::app()->session['itemcnt'] = '';
				$displayList = '';
				
				Yii::app()->yelpdb->zipCodes = array($model->zip);
				//Yii::app()->yelpdb->yelpCategories = array('all');
				
				//Yii::app()->yelpdb->yelpCategories = array(Yii::app()->yelpdb->Categories[$model->category]);

				//process the yelp search and insert into db
				Yii::app()->yelpdb->actionImport();

				//process the db contents and parse the contents
				$displayList = Yii::app()->yelpdb->actionParse(); 
				//print_r($displayList);
				$item_count = count($displayList);

				Yii::app()->session['searchList'] = $displayList;
				Yii::app()->session['itemcnt'] = $item_count;

				$postFlag = 1;
				
				//print_r($displayList); 
				
				
			}
				/*if(isset($_POST['yt1']))
				{
					$cc = "'".implode("','",$_POST['f_n'])."'";
					$checkval = Yii::app()->yelpdb->actionSave($cc, $_POST['yt1']);
					$postFlag = 1;
				}
				
				if(isset($_POST['yt2']))
				{
					$cc = "'".implode("','",$_POST['f_n'])."'";
					$checkval = Yii::app()->yelpdb->actionSave($cc, $_POST['yt2']);

				}
					$postFlag = 1;*/
				
			
		}

		//$criteria=new CDbCriteria();
		
		//$item_count = Search::model()->count($criteria);
		/*$pages=new CPagination($item_count); 
		
		// results per page 
		$pages->pageSize=10;
		//$pages->setPageSize(Yii::app()->params['listPerPage']);
		//$pages->applyLimit($criteria);

		print_r($displayList);*/

		
		//$dataProvider->setPagination($pages);

		//get all saved business
		$savedYelpIds = Yii::app()->yelpdb->actionSavedRec();
		
		// display the search form
		$this->render('search',array('model'=>$model,
									'dispList'=>Yii::app()->session['searchList'],
									'item_count'=>Yii::app()->session['itemcnt'],
									'page_size'=>50,
									'postFlag'=>$postFlag,
									'savedYelpIds'=>$savedYelpIds,
									//'pages' => $pages,
									//'dataProvider' => $dataProvider
									));
	}


	/**
	 * Save the record as User's favorite
	 */
	public function actionSaverec()
	{
		if (Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->createUrl('site/login'));
		}	

		$checkval = 0;
		//print_r($_POST);exit;
		if(isset($_POST))
		{
			//print_r($_POST);

			if(isset($_POST['yid']))
			{
				$checkval = Yii::app()->yelpdb->actionSave($_POST['yid'], $_POST['act']);
			}
				
				/*if(isset($_POST['yt2']))
				{
					$cc = "'".implode("','",$_POST['f_n'])."'";
					$checkval = Yii::app()->yelpdb->actionSave($cc, $_POST['yt2']);

				}
					$postFlag = 1;*/
				
			
		}
		
		if($checkval == 1 && $_POST['act'] == 'Save'){
			$returnStr = 'Saved Successfully!';
		}elseif($checkval == 1 && $_POST['act'] == 'Delete'){
			$returnStr = 'Deleted Successfully!';
		}elseif($checkval == 2){
			$returnStr = 'Already Saved!';
		}else{
			$returnStr = 'Action Failed. Please try again!';
		}
		
		// display the status
		echo $returnStr;
	}
	
	/**
	 * Show My favorite records for the Logged in User
	 */
	public function actionMyfav()
	{
		if (Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
		
		$filter = (isset($_POST['filter'])?$_POST['filter']:'all');
		//echo $_POST['filter'];exit;
		
		if($filter != 'all')
		{
			$selectlist = Yii::app()->yelpdb->actionShowFilter($filter); 

		}else{
			//process the yelp search and insert into db
			$selectlist = Yii::app()->yelpdb->actionShowFavs();
		}	
		
		$this->render('myfav',array('selectlist'=>$selectlist,
									'filteract'=>$filter,
									));
	}
	
	
	/**
	 * Show My favorite records for the Logged in User
	 */
	public function actionMyfavsave()
	{
		if (Yii::app()->user->isGuest){
			$this->redirect(Yii::app()->createUrl('site/login'));
		}
		
		$checkval = 0;
		//print_r($_POST);exit;
		if(isset($_POST))
		{
			if(isset($_POST['bid']))
			{
				$checkval = Yii::app()->yelpdb->actionSaveFavs($_POST['bid'], $_POST['act']);
			}
			
			if($checkval == 1){
				$returnStr = 'Changes Updated';
			}else{
				$returnStr = 'Action Failed. Please try again!';
			}
			
			
		}
		
		echo $returnStr;
	}
	
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	
	}
    
	 public function actionDynamiccities()
       {
    $data=Location::model()->findAll('parent_id=:parent_id', 
                  array(':parent_id'=>(int) $_POST['country_id']));
 
    $data=CHtml::listData($data,'id','name');
    foreach($data as $value=>$name)
    {
        echo CHtml::tag('option',
                   array('value'=>$value),CHtml::encode($name),true);
    }
}


}

