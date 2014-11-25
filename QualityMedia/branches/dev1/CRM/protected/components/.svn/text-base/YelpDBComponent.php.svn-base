<?php
/**
 * Import Yelp leads.
 *
 * @author Jakub Pospiech <jakub.pospiech@gmail.com>
 */
//Yii::import('ext.yelp.*');

class YelpDBComponent extends CComponent
{
    /**
     * @var integer $pageSize Results page size
     */
    public $pageSize = 20;

    /**
     * @var array $yelpCategories Yelp categories.
    */
    public $Categories = array(
        'active', 'arts', 'auto', 'barbers', 'cosmetics', 'spas', 'eyelashservice', 'hair_extensions', 'hairremoval',
        'hair', 'makeupartists', 'massage', 'medicalspa', 'othersalons', 'piercing', 'rolfing', 'skincare', 'tanning',
        'tattoo', 'education', 'eventservices', 'financialservices', 'bagels', 'bakeries', 'beer_and_wine', 'breweries',
        'bubbletea', 'butcher', 'csa', 'coffee', 'convenience', 'desserts', 'diyfood', 'donuts', 'farmersmarket',
        'fooddeliveryservices', 'foodtrucks', 'gelato', 'grocery', 'icecream', 'internetcafe', 'juicebars', 'pretzels',
        'shavedice', 'gourmet', 'streetvendors', 'tea', 'wineries', 'acupuncture', 'cannabis_clinics', 'chiropractors',
        'c_and_mh', 'dentists', 'diagnosticservices', 'physicians', 'hearingaidproviders', 'homehealthcare', 'hospice',
        'hospitals', 'lactationservices', 'laserlasikeyes', 'massage_therapy', 'medcenters', 'medicalspa',
        'medicaltransportation', 'midwives', 'nutritionists', 'occupationaltherapy', 'optometrists', 'physicaltherapy',
        'reflexology', 'rehabilitation_center', 'retirement_homes', 'speech_therapists', 'tcm', 'urgent_care',
        'weightlosscenters', 'homeservices', 'hotelstravel', 'localflavor', 'localservices', 'massmedia', 'nightlife',
        'pets', 'professional', 'publicservicesgovt', 'realestate', 'religiousorgs', 'afghani', 'african', 'newamerican',
        'tradamerican', 'arabian', 'argentine', 'asianfusion', 'australian', 'austrian', 'bangladeshi', 'bbq', 'basque',
        'belgian', 'brasseries', 'brazilian', 'breakfast_brunch', 'british', 'buffets', 'burgers', 'burmese', 'cafes',
        'cajun', 'cambodian', 'caribbean', 'catalan', 'cheesesteaks', 'chicken_wings', 'chinese', 'comfortfood', 'creperies',
        'cuban', 'czechslovakian', 'delis', 'diners', 'ethiopian', 'hotdogs', 'filipino', 'fishnchips', 'fondue', 'food_court',
        'foodstands', 'french', 'gastropubs', 'german', 'gluten_free', 'greek', 'halal', 'hawaiian', 'himalayan', 'hotdog',
        'hotpot', 'hungarian', 'iberian', 'indpak', 'indonesian', 'irish', 'italian', 'japanese', 'korean', 'kosher',
        'laotian', 'latin', 'raw_food', 'malaysian', 'mediterranean', 'mexican', 'mideastern', 'modern_european',
        'mongolian', 'moroccan', 'pakistani', 'persian', 'peruvian', 'pizza', 'polish', 'portuguese', 'russian',
        'salad', 'sandwiches', 'scandinavian', 'scottish', 'seafood', 'singaporean', 'soulfood', 'soup', 'southern',
        'spanish', 'steak', 'sushi', 'taiwanese', 'tapas', 'tapasmallplates', 'tex-mex', 'thai', 'turkish', 'ukrainian',
        'vegan', 'vegetarian', 'vietnamese', 'adult', 'antiques', 'galleries', 'artsandcrafts', 'auctionhouses', 'baby_gear',
        'bespoke', 'media', 'bridal', 'computers', 'cosmetics', 'deptstores', 'discountstore', 'drugstores', 'electronics',
        'opticians', 'fashion', 'fireworks', 'flowers', 'guns_and_ammo', 'hobbyshops', 'homeandgarden', 'jewelry', 'knittingsupplies',
        'luggage', 'medicalsupplies', 'mobilephones', 'musicalinstrumentsandteachers', 'officeequipment', 'outlet_stores', 'pawn',
        'personal_shopping', 'photographystores', 'shoppingcenters', 'sportgoods', 'thrift_stores', 'tobaccoshops', 'toys', 'uniforms',
        'watches', 'wholesale_stores', 'wigs',
    );

	public $yelpCategories = array();

	//$yelpCategories = $Categories;


    /**
     * @var array $zipCodes Location zip codes.
     */
    public $zipCodes = array(
        // Example for Miami
        33131, 33132, 33137, 33139, 33140, 33141,
    );

    /**
     * @var object $yelp Yelp object
     */
    protected $yelp;

    /**
     * @var object $db Database connection.
     */
    protected $db;

    /**
     * Returns the currently active database connection.
     * By default, the 'db' application component will be returned and activated.
     * @return CDbConnection the currently active database connection
     */
    public function getDbConnection()
    {
        if($this->db === null) {
            $this->db = Yii::app()->getComponent('db');

            if(!$this->db instanceof CDbConnection) {
                throw new CException('The "db" application component must be configured to be a CDbConnection object.');
            }
        }

        return $this->db;
    }

    /**
     * Initializes the command object.
     * This method is invoked after a command object is created and initialized with configurations.
     */
    public function init()
    {
        $this->yelp = Yii::app()->getComponent('yelpsrch');

        if($this->yelp === null) {
            throw new CException('Yelp component is missing');
        }

        ini_set('max_execution_time', 0); 
    }

    /**
     * Import Yelp leads.
     */
    public function actionImport()
    {
        $start = microtime(true);

        foreach($this->zipCodes as $zipCode) {
            foreach($this->Categories as $category) {
                // Set params
                $params = array(
                    'location'          => $zipCode,
                    'category_filter'   => $category,
                    'limit'             => $this->pageSize,
                );

				$result = $this->getTotalItemsCount($params);
                // Get items count and page count
                $itemCount = (int)$result['total'];

                $pageCount = (int)(($itemCount + $this->pageSize - 1) / $this->pageSize);

				//echo $itemCount."pg===".$pageCount;exit;

                // Debug info
                //printf("\nZip code: %-10s; Category: %-20s; Items: %-5d\n", $zipCode, $category, $itemCount);

                // Collect the data
                for($i = 0; $i < $pageCount; $i++) {
                    // Calculate data offsets
                    $params['offset'] = $i * $this->pageSize;

                    // Print debug info
                    //printf('Page: %-5d; Offset: %-5d; Zip Code: %-10s; Category: %-25s', $i, $params['offset'], $zipCode, $category);

                    try {
                       /* if($params['offset'] >= 1000) {
                            throw new CException('Maximum offset is 1000');
                        }*/

                        // Get results
                        //$result = $this->yelp->search($params);

                        foreach($result['businesses'] as $business) {
							$sql = "SELECT COUNT(id) AS qty, id FROM yelp_leads_data WHERE zipCode = '".$zipCode."' and category = '".$category."'";
							
							$query = $this->getDbConnection()->createCommand($sql)->queryRow();

							if($query['qty']>0){
								$this->getDbConnection()
                                ->createCommand('UPDATE yelp_leads_data SET business=:business, updatedAt=NOW() WHERE id=:id')
                                ->execute(array(
                                    ':business'     => serialize($business),
									':id'			=> $query['id'],
                                ));
							}else{
								$this->getDbConnection()
									->createCommand('INSERT INTO yelp_leads_data VALUES(NULL, :zipCode, :category, :business, NOW(), NOW())')
									->execute(array(
										':zipCode'      => $zipCode,
										':category'     => $category,
										':business'     => serialize($business),
									));
							}
                        }

                       // echo "     >> OK\n";
                    }
                    catch(CException $e) {
                        $message = $e->getMessage();
                        echo "     >> Error: {$message}\n";
                    }
                }
            }
        }

        //echo "\nDone (time: ".sprintf('%.3f', microtime(true)-$start)."s)\n";
    }

    /**
     * Parse yelp results.
     */
    public function actionParse()
    {
        $start = microtime(true);

		//string to display the list to user
		$displaySearchLists = array();
		$insertedRec = '';
		$newRecCnt = 0;
		$dupRecCnt = 0;

        //echo 'Calculating results number and pages number';

        // Count rows
        $zipCodes = implode(', ', $this->zipCodes);
		$category = "'".implode("','", $this->Categories)."'";

        $sql = "SELECT COUNT(id) AS qty FROM yelp_leads_data WHERE zipCode IN ({$zipCodes}) and category in (".$category.")";

        $query = $this->getDbConnection()->createCommand($sql)->queryRow();

        // Set up pagination
        $pageSize   = 500;
        $itemCount  = $query['qty'];
        $pageCount  = (int)(($itemCount + $pageSize - 1) / $pageSize);

		//echo "cnt===".$pageCount;exit;

		if($itemCount > 0){

			//echo $itemCount."     >> OK\n";
	//echo "SELECT id, business, category, zipCode FROM yelp_leads_data WHERE zipCode IN ({$zipCodes}) and category in (".$category.")";
			$select = $this->getDbConnection()->createCommand("SELECT id, business, category, zipCode FROM yelp_leads_data WHERE zipCode IN ({$zipCodes}) and category in (".$category.") LIMIT :limit OFFSET :offset");

			$sql = 'INSERT INTO
						yelp_leads
					VALUES(
						NULL, :ycategory, :yzipCode, 1, :isClaimed, :rating, :reviewCount, :name, :url, :phone, :snippetText, :image, :categories,
						:yelpId, :isClosed, :city, :displayAddress, :neighborhoods, :postalCode, :countryCode, :address, :latitude, :longitude, :stateCode, NOW(), NOW(), 0)
					ON DUPLICATE KEY UPDATE duplicates = duplicates+1';
			$insert = $this->getDbConnection()->createCommand($sql);

			//echo "Inserting results:\n";

			for($i = 0; $i < $pageCount; $i++) {
				$offset = $i * $pageSize;

			   // printf("Page: %-4d; Offset: %6d; Total rows: %6d\n", $i, $offset, $totalRows);

				$select->bindValue(':limit', $pageSize, PDO::PARAM_INT);
				$select->bindValue(':offset', $offset, PDO::PARAM_INT);

				$rec_cnt = 0;

				foreach($select->queryAll() as $row) {
					$business = unserialize($row['business']);

					//printf("    >> [%d] %s - %s", $row['id'], $row['zipCode'], $row['category']);

				   /* try {
						$this->checkCriteria($business);
					}
					catch(CException $e) {
						printf("    >> Item does not match criteria (%s)\n", $e->getMessage());
						continue;
					}*/

					//get the business reviews
					$businessReviews = Yii::app()->yelpsrch->business($business['id']);
					$reviewLists = '';

					if(empty($businessReviews)){
						continue;
					}

					//get all the reviews
					foreach($businessReviews['reviews'] as $eachReview){
						$reviewLists .= $eachReview['excerpt'].'***'.$eachReview['rating'].'|||';
					}

					$reviewLists = trim($reviewLists, '|||');

					//check for duplicate record
					//$dup_flag = $this->actionCheckDuplicate((isset($business['id']) ? $business['id'] : ''));

					$displaySearchLists[$rec_cnt]['yelpId'] = (isset($business['id']) ? $business['id'] : '');
					$displaySearchLists[$rec_cnt]['name'] = (isset($business['name']) ? $business['name'] : '');
					$displaySearchLists[$rec_cnt]['image'] = (isset($business['image_url']) ? $business['image_url'] : '');
					$displaySearchLists[$rec_cnt]['rating'] = (isset($business['rating']) ? $business['rating'] : '');
					$displaySearchLists[$rec_cnt]['reviewCount'] = (isset($business['review_count']) ? $business['review_count'] : '');
					$displaySearchLists[$rec_cnt]['phone'] = (isset($business['display_phone']) ? $business['display_phone'] : '');
					$displaySearchLists[$rec_cnt]['reviewText'] = (isset($reviewLists) ? $reviewLists : '');
					$displaySearchLists[$rec_cnt]['address'] = (isset($business['location']['display_address']) ? implode(', ', $business['location']['display_address']) : '');
					//$displaySearchLists[$rec_cnt]['duplicateRecord'] = $dup_flag;
					

					$insert->bindValue(':ycategory', $row['category'], PDO::PARAM_STR);
					$insert->bindValue(':yzipCode', $row['zipCode'], PDO::PARAM_STR);
					$insert->bindValue(':isClaimed', ($business['is_claimed'] === true ? 1 : 0), PDO::PARAM_INT);
					$insert->bindValue(':rating', $business['rating'], PDO::PARAM_STR);
					$insert->bindValue(':reviewCount', $business['review_count'], PDO::PARAM_INT);
					$insert->bindValue(':name', $business['name'], PDO::PARAM_STR);
					$insert->bindValue(':url', $business['url'], PDO::PARAM_STR);
					$insert->bindValue(':phone', isset($business['display_phone']) ? $business['display_phone'] : '', PDO::PARAM_STR);
					$insert->bindValue(':snippetText', isset($reviewLists) ? $reviewLists : '', PDO::PARAM_STR);
					$insert->bindValue(':image', isset($business['image_url']) ? $business['image_url'] : '', PDO::PARAM_STR);

					$categories = array();
					if(isset($business['categories'])) {
						foreach($business['categories'] as $category) {
							$categories[] = $category[0];
						}
					}
					
					$displaySearchLists[$rec_cnt]['categories'] = (isset($categories) ? implode(', ', $categories) : '');

					$insert->bindValue(':categories', implode(', ', $categories), PDO::PARAM_STR);
					$insert->bindValue(':yelpId', $business['id'], PDO::PARAM_STR);
					$insert->bindValue(':isClosed', ($business['is_closed'] === true ? 1 : 0), PDO::PARAM_INT);
					$insert->bindValue(':city', $business['location']['city'], PDO::PARAM_STR);

					$displayAddress = isset($business['location']['display_address']) ? implode(', ', $business['location']['display_address']) : '';
					$insert->bindValue(':displayAddress', $displayAddress, PDO::PARAM_STR);

					$neighborhoods = isset($business['location']['neighborhoods']) ? implode(', ', $business['location']['neighborhoods']) : '';
					$insert->bindValue(':neighborhoods', $neighborhoods, PDO::PARAM_STR);
					$insert->bindValue(':postalCode', isset($business['location']['postal_code']) ? $business['location']['postal_code'] : '', PDO::PARAM_STR);
					$insert->bindValue(':countryCode', $business['location']['country_code'], PDO::PARAM_STR);

					$address = isset($business['location']['address']) ? implode(', ', $business['location']['address']) : '';
					$insert->bindValue(':address', $address, PDO::PARAM_STR);
					$insert->bindValue(':latitude', isset($business['location']['coordinate']['latitude']) ? implode(', ', $business['location']['coordinate']['latitude']) : '', PDO::PARAM_STR);
					$insert->bindValue(':longitude', isset($business['location']['coordinate']['longitude']) ? implode(', ', $business['location']['coordinate']['longitude']) : '', PDO::PARAM_STR);
					$insert->bindValue(':stateCode', $business['location']['state_code'], PDO::PARAM_STR);

					$affectedRowsCnt = $insert->execute(); 
					$ins_id = Yii::app()->db->getLastInsertID();

					$ins_rec = "'".$ins_id."'";
					$cntDetails = $this->actionAlertCount($ins_rec);
					$newRecCnt += $cntDetails['newrec'];
					$dupRecCnt += $cntDetails['duprec'];
					$rec_cnt++;
					//echo "    >> OK\n";
				}
			}
			
			/*if(!empty($insertedRec)){
				$ins_rec = "'".implode("','",$insertedRec)."'";
				$cntDetails = $this->actionAlertCount($ins_rec);
				$displaySearchLists[0]['alertDet'] = $cntDetails;
			}*/
			$displaySearchLists[0]['alertDet']['newrec'] = $newRecCnt;
			$displaySearchLists[0]['alertDet']['duprec'] = $dupRecCnt;
		}
		return $displaySearchLists; 
        //printf(" Done (time: %.3fs)\n", microtime(true)-$start);
    }


	/**
     * check the record for duplicate.
     * @param $yelpID - id of the yelp record
     */
    public function actionCheckDuplicate($yelpID)
    {
		$flag = 0;
		if($yelpID != ''){
			$chk_sql = "SELECT COUNT(id) AS qty, id FROM user_business WHERE userid = '".Yii::app()->user->id."' and business_id = '".$yelpID."'";				
			$query = $this->getDbConnection()->createCommand($chk_sql)->queryRow();
			if($query['qty']>0){
				$flag = 1;
			}else{
				$flag = 0;
			}
		}
		
		return $flag;
	}

	/**
     * get the record which are saved.
     */
    public function actionSavedRec()
    {
		$savedId = array();
		$i = 0;
		
		$sql = "SELECT business_id FROM user_business WHERE userid = '".Yii::app()->user->id."'";				
		$query = $this->getDbConnection()->createCommand($sql);

		foreach($query->queryAll() as $row) {
			if($row['business_id']){
				$savedId[$i] = $row['business_id'];
				$i++;
			}
		}
		
		return $savedId;
	}

	/**
     * Update the record according to user action.
     * @param $yelpID - id of the yelp record
	 *		  $action - action of user('Save' or 'Mark As Favorite')
     */
    public function actionSave($yelpID, $action)
    {
		$flag = 2;
		//echo $action."---".$yelpID;
		if($action == 'Save'){
			$sql = "insert into user_business values('', '".Yii::app()->user->id."', '".$yelpID."', 'new', NOW())";
		}elseif($action == 'Mark As Favorite'){
			$sql = "update yelp_leads set user_saved = '1' WHERE yelp_id IN (".$yelpID.")";
		}elseif($action == 'Delete'){
			$sql = "delete from user_business WHERE business_id = '".$yelpID."'";
		}else{
			$sql = '';
			$flag = 0;
		}
		
		if($sql != ''){
			$chk_sql = "SELECT COUNT(id) AS qty, id FROM user_business WHERE userid = '".Yii::app()->user->id."' and business_id = '".$yelpID."'";				
			$query = $this->getDbConnection()->createCommand($chk_sql)->queryRow();
			if($query['qty']>0 && $action == 'Save'){
				$flag = 2;
			}else{
				$insert = $this->getDbConnection()->createCommand($sql);
				if($insert->execute()){
					$flag = 1;
				}else{
					$flag = 0;
				}
			}
		}

		return $flag;
	}

	/**
     * get the duplicates and new records count.
     * @param $dbID - id of the db record
     */
    public function actionAlertCount($dbID)
    {
		$alertCount = array();
		//echo $action."---".$yelpID;
		if($dbID != ''){
			$newSql = "select count(id) as cnt from yelp_leads WHERE id IN (".$dbID.") and duplicates=1";
			$newRecordSQL = $this->getDbConnection()->createCommand($newSql)->queryRow();
			$alertCount['newrec'] = $newRecordSQL['cnt'];
			
			$dupSql = "select count(id) as cnt from yelp_leads WHERE id IN (".$dbID.") and duplicates > 1";
			$dupRecordSQL = $this->getDbConnection()->createCommand($dupSql)->queryRow();
			$alertCount['duprec'] = $dupRecordSQL['cnt'];
		}
		

		return $alertCount;
	}
	
	/**
     * @return string SQL query for logged in users
     */
    public function actionShowFavs()
    {
		
	        $sel_sql= "select a.name, a.phone, a.address, a.rating, b.status, b.business_id from yelp_leads a join user_business b on b.business_id = a.yelp_id and b.userid = '".Yii::app()->user->id."'";
			$query = $this->getDbConnection()->createCommand($sel_sql)->queryAll();
		
		//print_r($query);
		
		return $query;
    }
	
	//to show filter value listings
	public function actionShowFilter($action)
    {
		
	        $sel_sql= "select a.name, a.phone, a.address, a.rating, b.status, b.business_id from yelp_leads a join user_business b on b.business_id = a.yelp_id and b.status = '".$action."' and b.userid = '".Yii::app()->user->id."'";
			$query = $this->getDbConnection()->createCommand($sel_sql)->queryAll();
		
		//print_r($query);
		
		return $query;
    }
	
	 public function actionSaveFavs($busiID,$action)
    {
		 $flag = 0;
			$upd_sql = 'update user_business set status = "'.$action.'" where business_id = "'.$busiID.'"';
			//echo $upd_sql;exit;
			$insert = $this->getDbConnection()->createCommand($upd_sql);
			if($insert->execute()){
				$flag = 1;
			}else{
				$flag = 0;
			}
		
		return $flag;
    }

    /**
     * Shows total results in selected zip codes.
     * @param integer $raw Show raw (1) or parsed data(0)
     */
    public function actionTotal($args)
    {
        $raw = (isset($args[0]) && $args[0] == 1);

        $zipCodes = implode(', ', $this->zipCodes);

        if($raw) {
            $sql = "SELECT COUNT(id) AS total FROM yelp_leads_data WHERE zipCode IN ({$zipCodes})";
        }
        else {
            $sql = "SELECT COUNT(id) AS total FROM yelp_leads WHERE postal_code IN ({$zipCodes})";
        }

        $select = $this->getDbConnection()->createCommand($sql)->queryRow();

        //printf("\nTotal results count: %d\n", $select['total']);
    }

    /**
     * Show SQL query for export
     */
    public function actionSql($args)
    {
        $counter    = count($this->zipCodes);
        $limit      = isset($args[0]) ? $args[0] : $counter;

        $zipCodes   = array();

        foreach($this->zipCodes as $zipCode) {
            $zipCodes[] = $zipCode;
            $counter--;

            if(count($zipCodes) == $limit || $counter == 0) {
                echo "\n".$this->getSql(implode(', ', $zipCodes))."\n";
                $zipCodes = array();
            }
        }
    }

    /**
     * @return string SQL query with provided zip codes
     */
    protected function getSql($zipCodes)
    {
        return "SELECT is_claimed, rating, review_count, name, url, phone, categories, city, postal_code, address, state_code
                FROM yelp_leads
                WHERE postal_code IN ({$zipCodes})
                ORDER BY postal_code, id";
    }
	
	

    /**
     * Calculate total results number.
     * @param array $params Params list
     * @return integer Total results number
     */
    protected function getTotalItemsCount($params)
    {
        $params['limit'] = 1;
        $result = $this->yelp->search($params);

        //return (int)$result['total'];
		return $result;
    }

    /**
     * Check if business matches provided criteria.
     * @param array $business Business details
     * @return boolean True if every criteria matches
     * @throws CException An exception with reject reason
     */
    public function checkCriteria($business)
    {
        if(!is_array($business)) {
            throw new CException('Param is not an array');
        }

        // if($business['rating'] > 3.5) {
        //     throw new CException(sprintf('Rating is too high (%s)', $business['rating']));
        // }

        $zipCode = isset($business['location']['postal_code']) ? $business['location']['postal_code'] : '';

        if(!in_array($zipCode, $this->zipCodes)) {
            throw new CException(sprintf('Zip code is not in range (%s)', $zipCode));
        }

        $phoneNumber = isset($business['display_phone']) ? trim($business['display_phone']) : '';

        if($phoneNumber == '') {
            throw new CException('Phone number is missing');
        }

        $displayAddress = isset($business['location']['display_address']) ? implode(', ', $business['location']['display_address']) : '';
        if(trim($displayAddress) == '') {
            throw new CException('Address is missing');
        }

        return true;
    }
	
	/**
     * Cron - mailing review comments details
     */
    public function actionSendMail()
    {
        $start = microtime(true);

		//string to display the list to user
		$displaySearchLists = array();
		
		//echo "SELECT * FROM yelp_leads WHERE yelp_id != '' order by id asc LIMIT 0,1"; 
        $select = $this->getDbConnection()->createCommand("SELECT * FROM yelp_leads WHERE yelp_id != '' order by createdAt desc ");


			$rec_cnt = 0;
			$mail_flag = 0;
			$mailFilter_flag = 0;
			//$reviewRating = '';
			//$reviewExcerpt = '';
					
			$subject='=?UTF-8?B?'.base64_encode('Yelp Review Details').'?=';
				$headers="From: QM Admin <admin@qualitymedia.com>\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/html; charset=iso-8859-1";
				$message='<table width="98%" border=1 style="margin:12px;color:#000;font-size:12px;text-align:center;">
						<tr><td><b>Name</b></td><td><b>Address</b></td><td><b>Phone</b></td><td><b>Link</b></td><td><b>Rating</b></td><td><b>Review</b></td>';

            foreach($select->queryAll() as $row) {
				//echo "<br>".$row['url']; 
				
				
					//print_r($row);exit;	
						
				//get the business reviews
				$businessReviews = Yii::app()->yelpsrch->business($row['yelp_id']);
				$reviewLists = '';
				$reviewRating = '';
				$reviewExcerpt = '';

				if(empty($businessReviews)){
					continue;
				}
				
				$cnt = 0;
				$message_temp = ''; //filter the business if this review is same in our db
				//get all the reviews
				foreach($businessReviews['reviews'] as $eachReview){
					$reviewLists .= $eachReview['excerpt'].'***'.$eachReview['rating'].'|||';
					$reviewRating = $eachReview['rating'];
					$reviewExcerpt = $eachReview['excerpt'];

					if($reviewRating <= '2.5'){
						$cnt++;
						$mail_flag = 1;
						$message_temp.='<tr>';
						if($cnt < 2){
							$message_temp.='	<td rowspan="*$*">'.($row['name']?$row['name']:'&nbsp;').'</td>
								<td rowspan="*$*">'.($row['display_address']?$row['display_address']:'&nbsp;').'</td>
								<td rowspan="*$*">'.($row['phone']?$row['phone']:'&nbsp;').'</td>
								<td rowspan="*$*">'.($row['url']?$row['url']:'&nbsp;').'</td>';
						}
						$message_temp.='<td>'.($reviewRating?$reviewRating:'&nbsp;').'</td>
								<td>'.($reviewExcerpt?$reviewExcerpt:'&nbsp;').'</td></tr>';
					}
				}
				$message_temp=str_replace('*$*',$cnt,$message_temp);
				
					

				$reviewLists = trim($reviewLists, '|||'); 
				if($row['snippet_text'] != $reviewLists){
					//$snippet = $row['snippet_text'].'|||'.$reviewLists;
					$snippet = $reviewLists;
					//echo "update yelp_leads set snippet_text = '".$snippet."' where id='".$row['id']."'";
					$update = $this->getDbConnection()->createCommand("update yelp_leads set snippet_text = '".$snippet."' where id='".$row['id']."'");
					$mailFilter_flag = 1;
					$message.=$message_temp;
				}		
					
				
				//print_r($reviewLists);
				
				//echo "<br>here".$reviewRating;				
				
            }
			$message.='<table>';
			if($mail_flag && $mailFilter_flag){
				mail(Yii::app()->params['adminEmail'],$subject,$message,$headers);
				echo "Mail sent<br>";
			}else{
				echo 'Mail not sent';
			}
        	
    }
}