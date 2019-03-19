<?php $baseUrl = $this->getApp()->getRequest()->getBaseUrl(); ?><!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?php echo $baseUrl . "/assets/"; ?>css/main.css">
    <link rel="stylesheet" href="<?php echo $baseUrl . "/assets/"; ?>css/plugins.css">
    <link rel="stylesheet" href="<?php echo $baseUrl . "/assets/"; ?>css/site.css">
    <script src="<?php echo $baseUrl . "/assets/"; ?>js/jquery-3.2.1.min.js"></script>
    <script src="<?php echo $baseUrl . "/assets/"; ?>js/holder.min.js"></script>
    <script src="<?php echo $baseUrl . "/assets/"; ?>js/h.js"></script>
    <script src="<?php echo $baseUrl . "/assets/"; ?>js/autoload.js"></script>
    <script src="<?php echo $baseUrl . "/assets/"; ?>js/common.js"></script>
    <title>Table View</title>
</head>
<body>
<?php
$this->widget("\Widgets\TableView", [
    'header' => [
        'id' => [
            'label' => '学生ID',
            'width' => '20%',
            'class' => 'text-left',
            'default' => '...',
        ],
        'name' => [
            'label' => '姓名',
            'class' => 'text-left',
            'default' => '...',
        ],
        'sex' => [
            'label' => '性别',
            'class' => 'text-center',
            'default' => '密',
        ],
        'score' => [
            'label' => '分数',
            'class' => 'text-center',
            'default' => '-',
        ],
        'level' => [
            'label' => '分数等级',
            'class' => 'text-center',
            'default' => '-',
        ],
    ],
    'data' => [
    ],
]);
?>
<?php
$pager = new \TestApp\TestPager();

$pager = new stdClass();
$pager->pageSize = 5;
$pager->totalCount = 1001;

$this->widget("\Widgets\TableView", [
    'header' => [
        'id' => [
            'label' => '学生ID',
            'width' => '20%',
            'class' => 'text-left',
            'default' => '...',
        ],
        'name' => [
            'label' => '姓名',
            'class' => 'text-left',
            'default' => '...',
        ],
        'sex' => [
            'label' => '性别',
            'class' => 'text-center',
            'default' => '密',
        ],
        'score' => [
            'label' => '分数',
            'class' => 'text-center',
            'default' => '-',
        ],
        'level' => [
            'label' => '分数等级',
            'class' => 'text-center',
            'default' => '-',
        ],
    ],
    'data' => [
        ["id" => 1, 'username' => 'charles', 'sex' => 'nan', 'score' => 60],
        ["id" => 2, 'username' => 'phpcroner', 'sex' => 'nan', 'score' => 60],
        ["id" => 3, 'username' => 'qingbing', 'scroe' => 60],
        ["id" => 3,],
    ],
    'dataProcessing' => function ($re) {
        if (!isset($re['score'])) {
            $level = "未参考";
        } elseif ($re['score'] < 60) {
            $level = "不合格";
        } elseif ($re['score'] < 80) {
            $level = "合格";
        } else {
            $level = "优秀";
        }
        return [
            'level' => $level,
        ];
    },
    'pager' => $pager,
]);
?>
</body>
</html>