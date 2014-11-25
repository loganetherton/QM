<?php
/* @var $this SiteController */
/* @var $model Search */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Search';
$this->breadcrumbs=array(
	'Search',
);

if(isset($_GET['page'])){
	$cur_page = $_GET['page'];
}else{
	$cur_page = 1;
	if(isset($postFlag) && $postFlag == 0){
		
		//Yii::app()->session->destroy();
		unset(Yii::app()->session['searchList']);
		Yii::app()->session['searchList'] = '';
		unset(Yii::app()->session['itemcnt']);
		Yii::app()->session['itemcnt'] = '';
		
	}
}

if(isset($_GET['srt'])){
	if($_GET['srt'] == 'all'){
		$cur_srt = '';
	}else{
		$cur_srt = $_GET['srt'];
	}
}else{
	$cur_srt = '';
}


?>

<h1>Search</h1>



<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<!--<p class="note">Enter below your search keywords:</p>
	
	<div class="row">
		<?php //echo $form->labelEx($model,'search'); ?>
		<?php //echo $form->textField($model,'search'); ?>
		<?php //echo $form->error($model,'search'); ?>
	</div>-->

	<div class="row">
		<?php echo $form->labelEx($model,'zip'); ?>
		<?php echo $form->textField($model,'zip',array('maxlength'=>6)); ?>
		<?php echo $form->error($model,'zip'); ?>
		<?php echo $form->hiddenField($model,'category',array('value'=>'all')); ?>
	</div>
	
	<!-- <div class="row">
		<?php //echo $form->labelEx($model,'Category'); ?>
		<?php /*echo CHtml::dropDownList('category','', array('active', 'arts', 'auto', 'barbers', 'cosmetics', 'spas', 'eyelashservice', 'hair_extensions', 'hairremoval',
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
			'watches', 'wholesale_stores', 'wigs',));*/ ?>
	</div> -->

	

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>
	
	<?php
		
		if($model->validate() || isset($_GET['page'])){ 
			$dispList = Yii::app()->session['searchList'];
				$item_count = Yii::app()->session['itemcnt'];
			?>
		<div style="margin:12px;background-color:#ff0000;color:#ffffff;font-size:12px;text-align:center;">
			<?php
				echo 'New Records Found: '.(isset($dispList[0]['alertDet']['newrec'])?$dispList[0]['alertDet']['newrec']:0);
				echo '<br />Duplicate Records Found: '.(isset($dispList[0]['alertDet']['duprec'])?$dispList[0]['alertDet']['duprec']:0);
			?>
		</div>
	<div>
	<table width="100%" border="0">
		<tr><td>
		<?php
				foreach(range('A', 'Z') as $letter) {
					if($cur_srt == $letter){
						echo '&nbsp;'.$letter.'&nbsp;';
					}else{
						echo '&nbsp;<a href="/yii/jalal/index.php?r=site/search&page=1&srt='.$letter.'">'.$letter.'</a>&nbsp;';
					}
				}

				if($cur_srt == ''){
					echo '&nbsp;All&nbsp;';
				}else{
					echo '&nbsp;<a href="/yii/jalal/index.php?r=site/search&page=1&srt=all">All</a>&nbsp;';
				}
		?>
		</td></tr>
	</table>
			<table width="100%" border="1" cellpadding="5" cellspacing="3">
			<!--<tr><td><?php //if($checkval == '1'): echo 'Selected Records Saved or Marked as Favorites'; endif; ?></td></tr>-->
			<tr><th>Name</th><th>Address</th><th>Phone</th><th>Rating</th><th>Action</th></tr>
			<?php
				
//print_r($dispList);//exit;
				//$id = '';
				if($item_count > 0){
					$filteredList = array();
					
					for($k=0;$k<$item_count;$k++){
						if($cur_srt != ''){
							if(strpos(strtolower($dispList[$k]['name']), strtolower($cur_srt)) === 0){
								$filteredList[] = $dispList[$k];
							}
						}else{
							break;
						}
					}
					
					if(count($filteredList) > 0){
						Yii::app()->session['filteredList'] = $filteredList;
					}

					Yii::app()->session['filteredItemCnt'] = count($filteredList);

					if($cur_srt != ''){
						$dispList = Yii::app()->session['filteredList'];
						$item_count = Yii::app()->session['filteredItemCnt'];
					}
					sort($dispList);
				}

			if($item_count > 0){
//print_r($dispList);exit; 
				for($i=($page_size*($cur_page-1));$i<=(($page_size*$cur_page)-1);$i++):
					
					if($i < $item_count){ 
						 ?>
						<tr>
						<td><?php echo $dispList[$i]['name']; ?></td>
						<td><?php echo $dispList[$i]['address']; ?></td>
						<td><?php echo $dispList[$i]['phone']; ?></td>
						<td><?php echo $dispList[$i]['rating']; ?></td>
						<td><?php 
								$reviewBtFlag = '';
								$review_arr = explode("|||", $dispList[$i]['reviewText']);
								$dispRevStr = '<div style="width:600px;font-size:12px;">
												<table width="100%" border="1" cellpadding="5" cellspacing="3">
												<tr><th>Review</th><th>Rating</th></tr>';
								foreach($review_arr as $each_rev){
									$eachRev = explode("***", $each_rev);
									if($eachRev[1] <= 2.5){
										$reviewStyle = 'background-color:#ff0000;color:#ffffff;';
										$reviewBtFlag = 'background-color:#ff0000;color:#ffffff;';
									}else{
										$reviewStyle = '';
									}
									$dispRevStr .= '<tr style="'.$reviewStyle.'"><td>'.$eachRev[0].'</td>
														<td>'.$eachRev[1].'</td></tr>';
									
									
								}
								$dispRevStr .= '</table></div>';
								
								// the link that may open the dialog
								echo CHtml::button('View Reviews', array(
								   'onclick'=>'$("#reviews'.$i.'").dialog("open"); return false;',
								   'style'=>$reviewBtFlag,
								));

								//echo $form->hiddenField($model,'yid'.$i,array('value'=>$dispList[$i]['yelpId']));
								if(in_array($dispList[$i]['yelpId'], $savedYelpIds)){
									$saveBt = 'Delete';
									$btColor = 'background-color:#ff0000;color:#ffffff;';
								}else{
									$saveBt = 'Save';
									$btColor = '';
								}
								
								echo CHtml::ajaxSubmitButton($saveBt,Yii::app()->createUrl('site/saverec'),
														array(
															'type'=>'POST',
															'data'=> 'js:{"yid": "'.$dispList[$i]['yelpId'].'", "act":document.getElementById("ajaxSubmitBtn'.$i.'").value }',
															'success'=>'js:function(data){ 
																							if(document.getElementById("ajaxSubmitBtn'.$i.'").value == "Delete" && data == "Deleted Successfully!"){
																								document.getElementById("ajaxSubmitBtn'.$i.'").value = "Save";
																								document.getElementById("ajaxSubmitBtn'.$i.'").style.background = "#EBEBEB";
																								document.getElementById("ajaxSubmitBtn'.$i.'").style.color = "#000000";
																							}else if(document.getElementById("ajaxSubmitBtn'.$i.'").value == "Save" && data == "Saved Successfully!"){
																								document.getElementById("ajaxSubmitBtn'.$i.'").value = "Delete";
																								document.getElementById("ajaxSubmitBtn'.$i.'").style.background = "#ff0000";
																								document.getElementById("ajaxSubmitBtn'.$i.'").style.color = "#ffffff";
																							}else{}
																							alert(data);
																						
																					}'           
														), array('id'=>'ajaxSubmitBtn'.$i,
																 'name'=>'ajaxSubmitBtn'.$i,
																 'style'=>$btColor)); 

								/*echo CHtml::button('Save',
												array(
													'submit'=>array('site/saverec',array('id'=>$dispList[$i]['yelpId'])),
													'confirm' => 'Are you sure?',
													'params'=>array('id'=>$dispList[$i]['yelpId']),
												)
											);
								
								echo CHtml::beginForm();
								
								echo CHtml::ajaxSubmitButton(
									'Save',
									array('site/search'),
									array(
										'data'=>date('H:i:s'),
										//'update'=>'#req_res02',
									)
								);
								 
								echo CHtml::endForm();*/
								 
							$this->beginWidget('zii.widgets.jui.CJuiDialog',array(
								'id'=>'reviews'.$i,
								// additional javascript options for the dialog plugin
								'options'=>array(
									'title'=>'Reviews',
									'autoOpen'=>false,
									'width'=>'650px',
									'resizable'=>false,
								),
								'htmlOptions'=>array('style'=>'width: 650px;'),

							));

								
								echo $dispRevStr;

							$this->endWidget('zii.widgets.jui.CJuiDialog');
							echo '</td></tr>';
					
				}else{
					break;
				}
				endfor;
				}else{
					echo '<tr>
						<td colspan="4">No Records Found</td></tr>';
				}
				?>
			</table>
			<? if($item_count > 1){ ?>
			<table>
			<tr><td>Go to page: 
				<?php 
				//echo $item_count."---".$page_size;
					for($p=1;$p<=ceil($item_count/$page_size);$p++){
						if($p == $cur_page){
							echo '&nbsp;'.$p.'&nbsp;';
						}else{
							echo '&nbsp;<a href="/yii/jalal/index.php?r=site/search&page='.$p.'&srt='.$cur_srt.'">'.$p.'</a>&nbsp;';
						}
					}
					
				
				
				/*$this->widget('CLinkPager', array(
							'currentPage'=>$cur_page,
							'itemCount'=>$item_count,
							'pageSize'=>$page_size,
							'maxButtonCount'=>5,
				))

					$dataProvider=new CArrayDataProvider(Yii::app()->session['searchList'], array(
																							'id'=>'srclists',
																							'keyField'=>'yelpId',
																							'keys'=>array('name','address', 'phone', 'rating'),
																							'pagination'=>array(
																							'pageSize'=>10,
																					),
															));
					
					$this->widget('zii.widgets.grid.CGridView', array(
						'dataProvider'=>$dataProvider,
						'columns'=>array(

							array(
								'name' => 'Name',          
								'type' => 'raw',
								'value' => 'CHtml::encode(@$data["name"])'
							),
							array(
								'name' => 'Address',          
								'type' => 'raw',
								'value' => 'CHtml::encode(@$data["address"])'
							),
							array( 
								'name' => 'Phone',          
								'type' => 'raw',
								'value' => 'CHtml::encode(@$data["phone"])'
							),
							array(
								'name' => 'Rating',          
								'type' => 'raw',
								'value' => 'CHtml::encode(@$data["rating"])'
							),
						),
						'enablePagination'=> true,
					));*/
				
				?>
				</ul>
			</td></tr>	
			</table>
			<? } ?>
	</div>
	
	
	<!--<div>
		<table width="100%">
			<tr>
				<td>
					<div class="row buttons">
						<?php //echo CHtml::submitButton('Save'); ?>
						<?php //echo CHtml::submitButton('Mark As Favorite'); ?>
					</div>
				</td>
			</tr>
		</table>
	</div>-->
<?php
	

	


 } ?>
	
<?php $this->endWidget(); ?>
</div><!-- form -->
