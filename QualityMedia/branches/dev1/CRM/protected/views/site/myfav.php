<?php
/* @var $this SiteController */
/* @var $model My Favorites */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - My Favorites';
$this->breadcrumbs=array(
	'Saved Leads',
);

$form=$this->beginWidget('CActiveForm', array(
	'id'=>'myfav-form',
	//'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
));

?>
<h1>Saved Leads</h1>
 
<table width="100%" border="1" cellpadding="5" cellspacing="3">

			<tr>
					<td colspan="5">Filter By : 
						<? 
						
						echo CHtml::beginForm();
						
						echo CHtml::dropDownList('filter', '', array('all' => 'Show All', 'new'=>'For Followup','called'=>'Called','pending'=>'Pending for Call','closed'=>'Closed'), array('options'=>array($filteract=>array('selected'=>'selected'))));
						
						echo CHtml::submitButton('Submit');
						?>
					</td>
			 </tr>

			<tr><th>Name</th><th>Address</th><th>Phone</th><th>Rating</th><th>Action</th></tr>
			<?php
				//echo 'test---'.$filteract;
				//print_r($selectlist);//exit;
				if(count($selectlist) > 0){
					foreach($selectlist as $i => $row){
							 ?>
							<tr>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['address']; ?></td>
							<td><?php echo $row['phone']; ?></td>
							<td><?php echo $row['rating']; ?></td>
							<td><?php 
							
							
							
							echo CHtml::dropDownList('ajaxDropBtn'.$i, '', array('new'=>'For Followup','called'=>'Called','pending'=>'Pending for Call','closed'=>'Closed') ,
								 array(//AJAX CALL.
										'ajax'  => array(
												'type'  => 'POST',
												'url' => CController::createUrl('site/myfavsave'),
												//'data' => array("bid" => "js:$row['business_id']", "act" => "js:this.value"),
												'data'=> 'js:{"bid": "'.$row['business_id'].'", "act":document.getElementById("ajaxDropBtn'.$i.'").value }',
												'success'=> 'js:function(data) {
														alert(data)
													}',
												//'error'=> 'function(){alert("AJAX call error..!!!!!!!!!!");}',
										),//end of ajax array().
										'id'=>'ajaxDropBtn'.$i,
										'options'=>array($row['status']=>array('selected'=>'selected'))
							));
							
							echo CHtml::endForm();	
							
							?></td>
							</tr>
					<?php }
				}else{
					echo '<tr><td colspan="5">No Records Found</td></tr>';
				}?>		
			</table>
			
<?php $this->endWidget(); ?>			