<?php
/**
 * Activity stats view for a single business
 * This actually used to be single page before I added the show link as a "hack"
 * so that I could remember which tba user was before switching the page
 * This was to explain the semi-weird structure of this page
 *
 * @author Shitiz Garg <mail@dragooon.net>
 */

$this->setPageTitle('Client Analytics');

$this->renderPartial('/layouts/tabs/client', array(
    'activity' => true,
    'data' => $data,
    'id' => $id,
    'period' => $period,
    'arpu' => $arpu,
));

$ticks = array();
$hovers = array();
$plot_data_views = array();
$plot_data_leads = array();
$plot_data_revenue = array();

$annotate = array();
foreach ($data['data_points']['num_page_views'] as $node) {
    $node[0] = new DateTime($node[0]);
    if ($period == '1m') {
        $ticks[] = array(count($ticks), $node[0]->format('d'));
        $hovers[] = $node[0]->format('D, d F Y');

        if ($node[0]->format('d') == 1) {
            $annotate[] = array(
                'text' => $node[0]->format('F Y'),
                'x' => count($ticks) - 1,
                'y' => $node[1] + 5,
            );
        }
    }
    else {
        $ticks[] = array(count($ticks), $node[0]->format('M'));
        $hovers[] = $node[0]->format('F Y');

        if ($node[0]->format('m') == 1) {
            $annotate[] = array(
                'text' => $node[0]->format('Y'),
                'x' => count($ticks) - 1,
                'y' => $node[1] + 5,
            );
        }
    }

    $plot_data_views[] = array(count($plot_data_views), $node[1]);
    $plot_data_leads[] = array(count($plot_data_leads), $data['data_points']['num_customer_actions'][count($plot_data_leads)][1]);
    $plot_data_revenue[] = array(count($plot_data_revenue), $data['data_points']['num_customer_actions'][count($plot_data_revenue)][1] * $arpu);
}
?>

<div id="main-content" class="row-fluid gran-data">
    <div id="ch-content" class="span12">
        <h2><i class="icon3-user"></i> Viewing stats for <?php echo $model->business->billingInfo->companyName ?></h2>
        <div class="btn-group" style="margin-bottom: 10px;">
            <a href="<?php echo $this->createUrl('activity/view', array('id' => $id, 'period' => '1m', 'show' => $show)) ?>" class="btn <?php echo $period == '1m' ? 'btn-primary' : '' ?>">30 days</a>
            <a href="<?php echo $this->createUrl('activity/view', array('id' => $id, 'period' => '1y', 'show' => $show)) ?>" class="btn <?php echo $period == '1y' ? 'btn-primary' : '' ?>">One year</a>
            <a href="<?php echo $this->createUrl('activity/view', array('id' => $id, 'period' => '2y', 'show' => $show)) ?>" class="btn <?php echo $period == '2y' ? 'btn-primary' : '' ?>">Two years</a>
        </div>
        <div id="views" class="page">
            <div id="placeholder_views" style="width: 100%; height: 300px;" class="plot"></div>
            <div class="well well-large">
                <h4>About your audience: <?php echo $data['formatted_text']['date_range'] ?></h4>
                <strong><?php echo $data['formatted_text']['num_mobile_page_views'] ?></strong> User views (<?php echo round(((int) $data['totals']['num_mobile_page_views'] / (int) $data['totals']['num_page_views']) * 100) ?>%) came from mobile devices<br />
                Your business has appeared in Yelp search results: <strong><?php echo $data['formatted_text']['num_search_appearances'] ?> times</strong>
            </div>
        </div>
        <div id="leads" class="page">
            <div id="placeholder_leads" style="width: 100%; height: 300px;" class="plot"></div>
            <div class="well well-large">
                <h4>Customer Leads breakdown: <?php echo $data['formatted_text']['date_range'] ?></h4>
                <div class="row" style="margin-left: 0;">
                    <div class="span3">
                        <?php echo $data['formatted_text']['num_check_ins'] ?> mobile checkins<br />
                        <?php echo $data['formatted_text']['num_directions'] ?> directions to business<br />
                        <?php echo $data['formatted_text']['num_deals_sold'] ?> deals sold
                    </div>
                    <div class="span3">
                        <?php echo $data['formatted_text']['num_calls'] ?> mobile calls<br />
                        <?php echo $data['formatted_text']['num_business_url_visits'] ?> clicks to your website
                    </div>
                    <div class="span3">
                        <?php echo $data['formatted_text']['num_photos'] ?> uploaded photos<br />
                        <?php echo $data['formatted_text']['num_bookmarks'] ?> Yelp bookmarks
                    </div>
                </div>
            </div>
        </div>
        <div id="revenue" class="page">
            <div id="placeholder_revenue" style="width: 100%; height: 300px;" class="plot"></div>
            <div class="well well-large">
                Yelp Customer Leads: <strong><?php echo Yii::app()->format->number($data['totals']['num_customer_actions']) ?></strong><br />
                Average Revneue per customer: <strong><?php echo Yii::app()->format->number($arpu) ?></strong>
                <hr />
                Estimated revenue from customers: <strong>$<?php echo Yii::app()->format->number($data['totals']['num_customer_actions'] * $arpu) ?></strong>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $this->resourceUrl('js/jquery.flot.min.js', 's3'); ?>" language="JavaScript"></script>
<script type="text/javascript">
$(function() {
    var numFormat = function(number) {
        return number.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
    };

    var data_views = <?php echo CJSON::Encode($plot_data_views); ?>;
    var data_leads = <?php echo CJSON::Encode($plot_data_leads); ?>;
    var data_revenue = <?php echo CJSON::Encode($plot_data_revenue); ?>;
    var annotate = <?php echo CJSON::Encode($annotate); ?>;
    var hover_labels = <?php echo CJSON::Encode($hovers); ?>;

    var ticks = <?php echo CJSON::Encode($ticks); ?>;

    var markings = [];
    for (i in annotate) {
        markings.push({ color: "#000", lineWidth: 2, xaxis: {from: annotate[i].x, to: annotate[i].x}});
   }

    var plot_options = {
        series: {
            lines: { show: false },
            points: { show: false }
        },
        xaxis: {
            ticks: ticks,
            color: '#fff',
            autoScaleMargin: 10
        },
        yaxis: {
            tickFormatter: numFormat
        },
        bars: {
            show: true,
            barWidth: 0.7
        },
        grid: {
            borderWidth: {
                top: 0,
                right: 0,
                bottom: 2,
                left: 0
            },
            hoverable: true,
            markings: markings
        },
        lines: {
            show: false
        },
        points: {
            show: false
        }
    };
    var plot_views = jQuery.plot("#placeholder_views", [
        {
            data: data_views
        }
    ], plot_options);
    var plot_leads = jQuery.plot("#placeholder_leads", [
        {
            data: data_leads,
            color: '#008080'
        }
    ], plot_options);

    plot_options.yaxis = {
        tickFormatter: function(val, axis)
        {
            return '$' + numFormat(val);
        }
    };

    var plot_revenue = jQuery.plot("#placeholder_revenue", [
        {
            data: data_revenue,
            color: '#76EE00'
        }
    ], plot_options);

    var annotate_el, o;
    for (i in annotate) {
        o = plot_views.pointOffset({x: annotate[i].x, y: annotate[i].y});
        annotate_el = jQuery("<div style='position:absolute;left:" + (o.left + 4) + "px;top:20px;color:#333;font-size:small'>" + annotate[i].text + "</div>");

        jQuery('#placeholder_views').append(annotate_el.clone());
        jQuery('#placeholder_leads').append(annotate_el.clone());
        jQuery('#placeholder_revenue').append(annotate_el.clone().css({left: o.left + 20}));
    }

    jQuery("#placeholder_views").bind("plothover", function(event, pos, item)
    {
        return processHover(item, "Views");
    });

    jQuery("#placeholder_leads").bind("plothover", function(event, pos, item)
    {
        return processHover(item, "Leads");
    });

    jQuery("#placeholder_revenue").bind("plothover", function(event, pos, item)
    {
        return processHover(item, "", '$');
    });

    var processHover = function(item, label, prefix)
    {
        $("#tooltip").remove();

        if (!item)
            return true;

        var x = item.datapoint[0].toFixed(0),
            y = item.datapoint[1].toFixed(0);

        showTooltip(item.pageX, item.pageY, hover_labels[x] + "<br /><strong style='font-size:1.1em;'>" + (typeof prefix !== 'undefined' ? prefix : '') + numFormat(item.datapoint[1]) + "</strong>&nbsp;" + label);
    };

    var showTooltip = function(x, y, contents) {
        jQuery("<div id='tooltip'>" + contents + "</div>").css({
            position: "absolute",
            display: "none",
            top: y + 5,
            left: x + 5,
            border: "2px solid #333",
            padding: "7px",
            "background-color": "#eee",
            opacity: 0.80
        }).appendTo("body").show();
    };

    jQuery('.nav-tabs li.activity a').on('click', function()
    {
        jQuery('.nav-tabs').find('.active').removeClass('active');
        jQuery(this).parent().addClass('active');

        var index = $('.nav-tabs li.activity a').index(this) + 3;
        jQuery('div.page').hide();
        jQuery('div.page:nth-child(' + index + ')').show();
    });

    jQuery('div.page').hide();
    var show = <?php echo $show; ?>;
    jQuery(jQuery('.nav-tabs li.activity a').get(show - 1)).click();
});
</script>