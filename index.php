<?php
require_once 'parser.php';

$output = '';

if($_POST) {
    $parser = new parser();
    $parser->run($_POST['text']);
    $output = $parser->getJSONOutput();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=UTF-8" http-equiv="content-type">
        <script type="text/javascript" src="js/awesomechart.js"></script>
        <style>

            body{
                background: #fff;
                color: #333;
            }

            a, a:visited, a:link, a:active{
                color: #333;
            }

            a:hover{
                color: #00f;
            }

            .charts_container{
                width: 900px;
                height: 500px;
                margin: 10px auto;
            }

            .chart_container_centered{
                text-align: center;
                width: 900px;
                height: 500px;
                margin: 10px auto;
            }

            .chart_container{
                width: 400px;
                height: 400px;
                margin: 0px 25px;
                float: left;
            }

            .footer{
                font-size: small;
                text-align: right;
            }
        </style>
    </head>
    <body>
        <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">

            <label for="text">Text to parse:</label><br />
            <textarea id="text" name="text" cols="150" rows="10">

            </textarea>
            <input type="submit" value="parsen">
        </form>
        <div>
            <h1>Output</h1>
            <table>
                <tr>
                    <td>
                    <div class="chart_container">

                    <canvas id="chartCanvas1" width="400" height="400">
                        Your web-browser does not support the HTML 5 canvas element.
                    </canvas>
                   </div>
                    </td>
                    <td>
                    <div class="chart_container">
                    <canvas id="chartCanvas2" width="400" height="400">
                        Your web-browser does not support the HTML 5 canvas element.
                    </canvas>
                   </div>
                    </td>
                </tr>
            </table>
        </div>

        <script type="text/javascript">
         var chart1 = new AwesomeChart('chartCanvas1');
            chart1.title = "Word types in %";
            chart1.chartType = "horizontal bars";
            var data = new Array();
            var labels = new Array();
            var data2 = new Array();

        <?php $content = json_decode($output);
            foreach($content->frequency as $label => $data): ?>
              labels.push('<?php echo $label; ?>');
              data.push(<?php echo $data ?>);

        <?php endforeach; ?>
            chart1.labels = labels;
            chart1.data = data;
            chart1.randomColors = true;
            chart1.draw();

        <?php foreach($content->matches as $data): ?>
            data2.push(<?php echo $data ?>);
        <?php endforeach; ?>

            var chart2 = new AwesomeChart('chartCanvas2');
            chart2.title = "Word types in text absolute";
            chart2.chartType = "horizontal bars";
            chart2.labels = labels;
            chart2.data = data2;
            chart2.randomColors = true;
            chart2.draw();
        </script>
    </body>
</html>
 
