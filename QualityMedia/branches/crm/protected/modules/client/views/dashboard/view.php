<?php
/**
 * @var $model Yelp
 */
?>
<div class="well snapshot well-dark">
    <div class="row-fluid">
        <h3>Yelp Snapshot</h3>
        <div class="span4 brd first-col">
            <div class="well item">
                <span class="head"><i class="icon2-address"></i> New Traffic*</span>
                <div class="content">
                    <h2 class="value">+<?=$visitsSinceHire?></h2>	
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="well item">
                <span class="head"><i class="icon3-book"></i> Responses Written*</span>
                <div class="content">
                    <h2 class="value">+<?=$responsesWrittenSinceHire?></h2>	
                </div>
            </div>
        </div>				
        <div class="span4">
            <div class="well item">
                <span class="head"><i class="icon3-book"></i> Customer Interactions*</span>
                <div class="content">
                    <h2 class="value">+<?=$customerInteractionsSinceHire?></h2>	
                </div>
            </div>
        </div>
        <span class="pull-right note">*since hiring Quality Media</span>				
    </div>
</div>
<div>
    <div class="row-fluid">
        <div class="span4">
            <div class="well chart-box">
                <div class="pull-left"><i class="icon-signal"></i> Traffic</div>
                <div class="pull-right"><i class="icon-question-sign"></i></div>
                <div id="chart-area" style="height: 300px; width: 100%;"></div>
            </div>
        </div>
        <div class="span4">
            <div class="well chart-box">
                <div class="pull-left"><i class="icon-signal"></i> Customer Interaction</div>
                <div class="pull-right"><i class="icon-question-sign"></i></div>
                <div id="chart-area2" style="height: 300px; width: 100%;"></div>
            </div>
        </div>				
    </div>
</div>
<div>
    <div class="row-fluid">
        <div class="span8">
            <div class="well soc-stat">
                <h2 class="head">What have we done for you lately?</h2>
                <ul class="listed">
                    <?php foreach($activities as $activity): ?>
                    <li class="soc-yp">
                        <p class="title">We left a <?php echo $activity['type'] == 'public'?'public comment':'private message';?> to <?php echo $activity['review_author']; ?></p>
                        <p class="post"><?php echo $activity['message']; ?></p>
                        <p class="date">Posted on <?php echo $activity['date']; ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>	
            </div>
        </div>
        <div class="span4 side-right">
            <div class="well boxed bg-white">
                <div class="head clearfix">
                    <span class="pull-left no-ico">Your Account Manager</span>
                </div>
                <div class="inn">
                    <div class="acc-manager clearfix">
                        <div class="info">
                            <h3><?php echo $accountManagerModel->FullName; ?></h3>
                            <p class="phone">N/A</p>
                            <a href="#" class="email"><?php echo $accountManagerModel->email; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->resourceUrl('fuelux/jquery.js','s3'); ?>"></script>

<script src="<?php echo $this->resourceUrl('fuelux/require.js','s3'); ?>"></script>


<script type="text/javascript">
  window.onload = function () {
      var chart = new CanvasJS.Chart("chart-area", {
          theme: "theme2",//theme1
          title:{
              text: ""              
         },
          data: [              
          {
              type: "column",
              dataPoints: [
              { label: "Last 30 Days", y: <?php echo $visitsLastThirtyDays; ?> },	                  
              ]
          }
          ]
      });

      chart.render();

      var chart2 = new CanvasJS.Chart("chart-area2", {
          theme: "theme2",//theme1
          title:{
              text: ""              
         },
          data: [              
          {
              type: "column",
              dataPoints: [
              { label: "Last 30 Days", y: <?php echo $customerInteractionsLastThirtyDays;?> },	                  
              ]
          }
          ]
      });

      chart2.render();

      var chart3 = new CanvasJS.Chart("chart-area3", {
          theme: "theme2",//theme1
          title:{
              text: ""              
         },
          data: [              
          {
              type: "column",
              dataPoints: [
              { label: "Last 30 Days", y: 0 },	                  
              ]
          }
          ]
      });

      chart3.render();
  }
</script>	