<?php
/**
 * 分析用户数据
 * @author LincolnZhou<875199116@qq.com>
 */
require_once '../core/bootstrap.php';
require_once '../core/config.php';
require_once '../core/db.php';

//总人数
$total = Db::getInstance()->query('select count(*) as count from user');

//采集时间
$first_time =Db::getInstance()->query('select add_time from user order by id ASC limit 1');
$last_time = Db::getInstance()->query('select add_time from user order by id DESC limit 1');

//性别
$genders = Db::getInstance()->query('select sex, count(*) as count from user where sex is not null group by sex order by sex ASC');
//地区
$locations = Db::getInstance()->query('select location, count(*) as count from user group by location');
$keywords = $GLOBALS['location'];
$locations_echo = array();
foreach ($keywords as $search) {
    $locations_echo[$search] = 0;
}
foreach ($locations as $location) {
    $isHasLocation = false;
    foreach ($keywords as $keyword) {
        if (!is_bool(strpos($location['location'], $keyword))) {
            $locations_echo[$keyword] += $location['count'];
            $isHasLocation = true;
        }
    }

    if ($isHasLocation == false) {
        //$locations_echo['未知'] += $location['count'];
    }
}

//职业
$jobs = Db::getInstance()->query('select job, count(*) as count from user group by job order by job ASC');

//标签统计
$tagsLibrary = Db::getInstance()->query('select * from user_tag');
$tags = Db::getInstance()->query('select tags_str from user');

$tags_echo = array();
$tags_name = array();
foreach ($tagsLibrary as $search) {
    $tags_echo[$search['name']] = 0;
    $tags_name[] = $search['name'];
}

foreach ($tags as $tag) {
    $intersect = array_intersect(explode(', ', $tag['tags_str']), $tags_name);
    foreach ($intersect as $item) {
        $tags_echo[$item]++;
    }
}

$codingJobs = Db::getInstance()->query("select job, count(*) as count from user  where company like '%coding%' or company like '%Coding%' group by job order by job ASC");
$codingCounts = Db::getInstance()->query("select count(*) as count from user  where company like '%coding%' or company like '%Coding%'");
$codingGenders = Db::getInstance()->query("select sex, count(*) as count from user where company like '%coding%' or company like '%Coding%' and sex is not null group by sex order by sex ASC");

$endTime = $last_time[0]['add_time'];
$startTime = strtotime('-1 day', $endTime);
$preTwoDaysTime = strtotime('-2 days', $endTime);
$oneDayTotalUsers = Db::getInstance()->query("select count(*) as count from user  where last_activity_at >= " .$startTime*1000 . " and last_activity_at <= " . $endTime*1000);
$twoDayTotalUsers = Db::getInstance()->query("select count(*) as count from user  where last_activity_at >= " .$preTwoDaysTime*1000 . " and last_activity_at <= " . $endTime*1000);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Coding.net用户分析">
    <meta name="author" content="周仕林">
    <link rel="icon" href="favicon.ico">
    <title>Coding.net用户分析</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-default" id="nav_header">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">
                <img alt="Brand" src="img/coding-logo.png" id="img_logo">
            </a>
            <h4 class="navbar-text" id="logo_text">Coding.net用户数据分析</h4>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h3>采集总用户数：<?php echo $total[0]['count']; ?></h3>
        </div>
        <div class="col-md-8">
            <h3>采集时间：<?php echo  date('Y-m-d H:i:s', $first_time[0]['add_time']) . ' ~ ' .  date('Y-m-d H:i:s', $last_time[0]['add_time']); ?></h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div id="chart_gender"></div>
        </div>
        <div class="col-md-6">
            <div id="chart_location"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div id="chart_job"></div>
        </div>
        <div class="col-md-6">
            <div id="chart_tag"></div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <h4>Coding.net公司总人数(参考):<?php echo $codingCounts[0]['count']; ?></h4>
            <div id="chart_coding_job"></div>
        </div>
        <div class="col-md-6">
            <div id="chart_coding_gender"></div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div><h4>访问量分析:</h4></div>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>名称</th>
                    <th>人数</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"><?php echo date('Y-m-d H:i:s', $startTime); ?> ~ <?php echo date('Y-m-d H:i:s', $endTime); ?> </th>
                    <td><?php echo $oneDayTotalUsers[0]['count']; ?></td>
                </tr>
                <tr>
                    <th scope="row"><?php echo date('Y-m-d H:i:s', $preTwoDaysTime); ?> ~ <?php echo date('Y-m-d H:i:s', $startTime); ?></th>
                    <td><?php echo $twoDayTotalUsers[0]['count']; ?></td>
                </tr>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/highcharts.js"></script>
</body>
<script>
    $(function () {
        $('#chart_gender').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '用户男女比例'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['男', <?php echo $genders[0]['count']; ?>],
                    ['女', <?php echo $genders[1]['count']; ?>],
                    ['未知', <?php echo $genders[2]['count']; ?>]
                ]
            }]
        });
    });

    $(function () {
        $('#chart_location').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '用户人群地区分布'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    <?php foreach ($locations_echo as $location => $count) { ?>
                    ['<?php echo $location;?>', <?php echo $count;?>],
                    <?php } ?>
                ]
            }]
        });
    });

    $(function () {
        $('#chart_job').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '用户职业分布'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['未知', <?php echo $jobs[0]['count']; ?>],
                    ['开发', <?php echo $jobs[1]['count']; ?>],
                    ['产品', <?php echo $jobs[2]['count']; ?>],
                    ['设计', <?php echo $jobs[3]['count']; ?>],
                    ['运维', <?php echo $jobs[4]['count']; ?>],
                    ['运营', <?php echo $jobs[5]['count']; ?>],
                    ['打杂', <?php echo $jobs[6]['count']; ?>],
                    ['测试', <?php echo $jobs[7]['count']; ?>],
                    ['市场', <?php echo $jobs[8]['count']; ?>]
                ]
            }]
        });
    });

    $(function () {
        $('#chart_tag').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '用户标签分布'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    <?php foreach ($tags_echo as $tag => $count) { if ($count) { ?>
                    ['<?php echo $tag;?>', <?php echo $count;?>],
                    <?php }} ?>
                ]
            }]
        });
    });

    $(function () {
        $('#chart_coding_job').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Coding.net工作分布'
            },
            tooltip: {
                pointFormat: '{series.name}({point.y}人): <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}({point.y}人)</b>: {point.percentage:.1f}%'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['未知', <?php echo $codingJobs[0]['count']; ?>],
                    ['开发', <?php echo $codingJobs[1]['count']; ?>],
                    ['产品', <?php echo $codingJobs[2]['count']; ?>],
                    ['设计', <?php echo $codingJobs[3]['count']; ?>],
                    ['运维', <?php echo $codingJobs[4]['count']; ?>],
                    ['运营', <?php echo $codingJobs[5]['count']; ?>],
                    ['打杂', <?php echo $codingJobs[6]['count']; ?>],
                    ['测试', <?php echo $codingJobs[7]['count']; ?>],
                    <?php if (isset($codingJobs[8]['count'])) { ?>['市场', <?php echo $codingJobs[8]['count']; ?>]<?php }?>
                ]
            }]
        });
    });

    $(function () {
        $('#chart_coding_gender').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '用户男女比例'
            },
            tooltip: {
                pointFormat: '{series.name}({point.y}人): <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        format: '<b>{point.name}({point.y}人)</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Browser share',
                data: [
                    ['男', <?php echo $codingGenders[0]['count']; ?>],
                    ['女', <?php echo $codingGenders[1]['count']; ?>],
                    ['未知', <?php echo $codingGenders[2]['count']; ?>]
                ]
            }]
        });
    });
</script>
</html>