<table style="width:100%; margin-top: 1px; background:#efefef; border-collapse: collapse">
    <tr style='border-bottom:1px solid #fff; margin-left: 2px'>
        <td colspan="4" style="border:0px solid #efefef;padding-left: 5px;width:100%;background:white; font-size: 15px">
        Hi, [Contact Person]!
        </td>
    </tr>
    <tr>
        <td class='imageContainer' style="border: 0px solid #fff;border-right:0px;width:10%;background:#efefef;padding:10px; max-height: 75px">
            <?php if (isset($photo) && !empty($photo)) { ?>
                <img src="<?php echo $photo->photoUrl; ?>" alt="Client photo"/>
            <?php } else { ?> No photo available <?php } ?>
        </td>
        <td  style="border:0px solid #fff;border-left:0px;width:55%;background:#efefef;padding-left:10px; max-height: 75px">
            <div style='font-weight:bold;font-size:12px'>
                Data Range Report for
            </div>
            <div style='font-size:24px; padding-top: 7px'>
                <?php echo $clientLabel;?>
            </div>
            
        </td>
        <td style="width:5%;background:#289BFD;padding:10px;vertical-align:top;padding-top:12px">
            <?php echo CHtml::image($this->resourceUrl('emailReport/images/calendar.png', 's3'), 'calendar'); ?>
        </td>
        <td style="width:5%;background:#289BFD;padding:10px;text-align:right;color:#fff">
            <small style="font-weight:normal;font-size:12px;border-right: 0px"><?php echo $startDateFormattedWords . ' - ' . $endDateFormattedWords?></small>
            <h4 style="font-size:22px; color:white">Yelp</h4>
        </td>
    </tr>
        
        <?php
        // Ad clicks daily data points
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['num_ad_clicks_daily'],
            'id' => $id,
            'title' => 'Ad clicks (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_ad_clicks_daily',
            )
        );
        // Page views daily data points
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['num_page_views_daily'],
            'id' => $id,
            'title' => 'Page views (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_page_views_daily',
            )
        );
        // Customer actions daily data points
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['num_customer_actions_daily'],
            'id' => $id,
            'title' => 'Customer actions (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_customer_actions_daily',
            )
        );
        // Has ads daily data points
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['has_ads_daily'],
            'id' => $id,
            'title' => 'Client has ads (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'has_ads_daily',
            )
        );
        // ARPU for this day
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['arpu_this_day'],
            'id' => $id,
            'title' => 'ARPU (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'arpu_this_day',
            )
        );
        // Mobile percentage for this day
        $this->renderPartial('/emailReport/_contentViewGraphsDaily', array(
            'data' => $data['mobile_percent_this_day'],
            'id' => $id,
            'title' => 'Mobile percentage (daily data points)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'mobile_percent_this_day',
            )
        );
        /**
         * Everything below here are 30 day totals
         */
        // Page views (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_page_views'],
            'id' => $id,
            'title' => 'Page views (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_page_views',
            )
        );
        // Check-ins (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_check_ins'],
            'id' => $id,
            'title' => 'Check-ins (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_check_ins',
            )
        );
        // Photos (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_photos'],
            'id' => $id,
            'title' => 'Number of photos (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_photos',
            )
        );
        // Open table reservations (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_opentable_reservations'],
            'id' => $id,
            'title' => 'Open table reservations (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_opentable_reservations',
            )
        );
        // Directions and maps (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_directions_and_map_views'],
            'id' => $id,
            'title' => 'Directions and map requests (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_directions_and_map_views',
            )
        );
        // CTA (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_cta_clicks'],
            'id' => $id,
            'title' => 'CTA (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_cta_clicks',
            )
        );
        // Bookmarks (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_bookmarks'],
            'id' => $id,
            'title' => 'Number of bookmarks (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_bookmarks',
            )
        );
        // Business URL visits (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_business_url_visits'],
            'id' => $id,
            'title' => 'Business URL visits (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_business_url_visits',
            )
        );
        // Mobile page views (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_mobile_page_views'],
            'id' => $id,
            'title' => 'Mobile page views (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_mobile_page_views',
            )
        );
        // Search appearances (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_search_appearances'],
            'id' => $id,
            'title' => 'Search appearances (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_search_appearances',
            )
        );
        // Deals sold (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_deals_sold'],
            'id' => $id,
            'title' => 'Deals sold (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_deals_sold',
            )
        );
        // Ad clicks (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_ad_clicks'],
            'id' => $id,
            'title' => 'Ad clicks (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_ad_clicks',
            )
        );
        // Calls (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_calls'],
            'id' => $id,
            'title' => 'Calls (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_calls',
            )
        );
        // Directions (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_directions'],
            'id' => $id,
            'title' => 'Directions requests (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_directions',
            )
        );
        // Customer actions (30 day totals)
        $this->renderPartial('/emailReport/_contentViewGraphs', array(
            'data' => $data['num_customer_actions'],
            'id' => $id,
            'title' => 'Customer actions (monthly averages)',
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dateRange' => $dateRange,
            'model' => $model,
            'today' => $today,
            'graphName' => 'num_customer_actions',
            )
        );
    ?>
    <tr style="width:100%;background:white">
        <td colspan="4" style="border:0px;width:100%;border-bottom: 1px solid rgb(239, 239, 239)">
        </td>
    </tr>
    <tr>
        <td colspan="4" style="border-top:1px solid #fff;border-right:0px;width:100%;background:white; font-size: 15px">
            Sincerely,<br>
            [Account Manager]
        </td>
    </tr>
</table>