<div style="text-align:center;">
    <canvas id="growth" height="200" width="1000" style="margin:0px auto;">[No canvas support]</canvas>
</div>

<?php
$results = $model->getSubscriptionsPerMonth();
$labels = CJSON::encode(array_keys($results));
$values = CJSON::encode(array_values($results));
$valuesCount = count($results)-1;

$graphsUrl = $this->resourceUrl('/js/graphs', 's3').'/';
$cs = Yii::app()->getClientScript();

$cs->registerScriptFile($graphsUrl.'RGraph.common.core.js');
$cs->registerScriptFile($graphsUrl.'RGraph.common.dynamic.js');
$cs->registerScriptFile($graphsUrl.'RGraph.common.effects.js');
$cs->registerScriptFile($graphsUrl.'RGraph.common.key.js');
$cs->registerScriptFile($graphsUrl.'RGraph.bar.js');
$cs->registerScriptFile($graphsUrl.'RGraph.pie.js');
$cs->registerScriptFile($graphsUrl.'RGraph.hbar.js');
$cs->registerScriptFile($graphsUrl.'RGraph.line.js');

$cs->registerScript('bar-chart', "

    // Create the br chart. The arguments are the ID of the canvas tag and the data
    var line = new RGraph.Line('growth', {$values});

    line.Set('chart.curvy', true);
    line.Set('chart.linewidth', 2);

    line.Set('chart.labels', {$labels});
    line.Set('chart.colors', ['#39b6e3']);
    line.Set('chart.text.size', 8);
    line.Set('chart.background.grid.dashed');
    line.Set('chart.noaxes', true);
    line.Set('chart.background.grid.autofit.numhlines', 2);
    line.Set('chart.background.grid.autofit.numvlines', {$valuesCount});


    // Now call the .Draw() method to draw the chart
    line.Draw();
");