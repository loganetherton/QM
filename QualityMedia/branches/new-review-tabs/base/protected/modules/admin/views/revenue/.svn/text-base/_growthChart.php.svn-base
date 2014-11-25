<div class="span12" id="ch-content">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th class="top1">Subscription Growth for 6-month period</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="margin: 0; padding: 0; text-align: center">
                    <canvas id="growth" height="300" width="1000">[No canvas support]</canvas>
                    <?php
                        $results = $model->getSubscriptionsPerMonth();
                        $labels = CJSON::encode(array_keys($results));
                        $values = CJSON::encode(array_values($results));

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
                            var bar = new RGraph.Bar('growth', {$values});

                            bar.Set('key.position.gutter.boxed', false);
                            bar.Set('key.position', 'gutter');
                            bar.Set('key.background', 'rgb(255,255,255)');

                            bar.Set('chart.xlabels.offset', 10);
                            bar.Set('chart.gutter.left', 0);
                            bar.Set('chart.gutter.bottom', 0);
                            bar.Set('chart.labels', {$labels});
                            bar.Set('chart.text.angle', 45);
                            bar.Set('chart.colors', ['#A4C639']);


                            // Now call the .Draw() method to draw the chart
                            bar.Draw();
                        ");
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>