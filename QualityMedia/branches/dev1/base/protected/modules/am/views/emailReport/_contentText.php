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
        <td style="width:15%;background:#289BFD;padding:10px;text-align:right;color:#fff">
            <small style="font-weight:normal;font-size:12px;border-right: 0px"><?php echo $startDateFormattedWords . ' - ' . $endDateFormattedWords?></small>
            <h4 style="font-size:22px; color:white">Yelp</h4>
        </td>
    </tr>
        
        <?php
        // Ad clicks daily data points
        if (array_filter($data['num_ad_clicks_daily'])) {
            $this->renderPartial('/emailReport/_contentViewsTextDaily', array(
                'data' => $data['num_ad_clicks_daily'],
                'title' => 'Ad clicks (daily data points)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Page views daily data points
        if (array_filter($data['num_page_views_daily'])) {
            $this->renderPartial('/emailReport/_contentViewsTextDaily', array(
                'data' => $data['num_page_views_daily'],
                'title' => 'Page views (daily data points)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Customer actions daily data points
        if (array_filter($data['num_customer_actions_daily'])) {
            $this->renderPartial('/emailReport/_contentViewsTextDaily', array(
                'data' => $data['num_customer_actions_daily'],
                'title' => 'Customer actions (daily data points)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Has ads daily data points
//        if (array_filter($data['has_ads_daily'])) {
//            $this->renderPartial('/emailReport/_contentViewsTextDaily', array(
//                'data' => $data['has_ads_daily'],
//                'title' => 'Client has ads (daily data points)',
//                'startDate' => $startDate,
//                'endDate' => $endDate,
//                'model' => $model,
//            ));
//        }
        // ARPU for this day
//        if (array_filter($data['arpu_this_day'])) {
//$this->renderPartial('/emailReport/_contentViewsTextDaily', array(
//            'data' => $data['arpu_this_day'],
//            'title' => 'ARPU (daily data points)',
//            'startDate' => $startDate,
//            'endDate' => $endDate,
//            'model' => $model,
//            )
//        );
        // Mobile percentage for this day
//        if (array_filter($data['mobile_percent_this_day'])) {
//$this->renderPartial('/emailReport/_contentViewsTextDaily', array(
//            'data' => $data['mobile_percent_this_day'],
//            'title' => 'Mobile percentage (daily data points)',
//            'startDate' => $startDate,
//            'endDate' => $endDate,
//            'model' => $model,
//            )
//        );
        /**
         * Everything below here are 30 day totals
         */
        // Page views (30 day totals)
        if (array_filter($data['num_page_views'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_page_views'],
                'title' => 'Page views (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Check-ins (30 day totals)
        if (array_filter($data['num_check_ins'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_check_ins'],
                'title' => 'Check-ins (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Photos (30 day totals)
        if (array_filter($data['num_photos'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_photos'],
                'title' => 'Number of photos (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Open table reservations (30 day totals)
        if (array_filter($data['num_opentable_reservations'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_opentable_reservations'],
                'title' => 'Open table reservations (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
}
        // Directions and maps (30 day totals)
        if (array_filter($data['num_directions_and_map_views'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_directions_and_map_views'],
                'title' => 'Directions and map requests (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // CTA (30 day totals)
        if (array_filter($data['num_cta_clicks'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_cta_clicks'],
                'title' => 'Call-to-Action (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Bookmarks (30 day totals)
        if (array_filter($data['num_bookmarks'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_bookmarks'],
                'title' => 'Number of bookmarks (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Business URL visits (30 day totals)
        if (array_filter($data['num_business_url_visits'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_business_url_visits'],
                'title' => 'Business URL visits (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Mobile page views (30 day totals)
        if (array_filter($data['num_mobile_page_views'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_mobile_page_views'],
                'title' => 'Mobile page views (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Search appearances (30 day totals)
        if (array_filter($data['num_search_appearances'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_search_appearances'],
                'title' => 'Search appearances (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Deals sold (30 day totals)
        if (array_filter($data['num_deals_sold'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_deals_sold'],
                'title' => 'Deals sold (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Ad clicks (30 day totals)
        if (array_filter($data['num_ad_clicks'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_ad_clicks'],
                'title' => 'Ad clicks (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Calls (30 day totals)
        if (array_filter($data['num_calls'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_calls'],
                'title' => 'Calls (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Directions (30 day totals)
        if (array_filter($data['num_directions'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_directions'],
                'title' => 'Directions requests (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
        // Customer actions (30 day totals)
        if (array_filter($data['num_customer_actions'])) {
            $this->renderPartial('/emailReport/_contentViewsText', array(
                'data' => $data['num_customer_actions'],
                'title' => 'Customer actions (collection of 30 day totals)',
                'startDate' => $startDate,
                'endDate' => $endDate,
                'model' => $model,
            ));
        }
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