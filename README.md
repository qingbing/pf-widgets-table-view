# pf-widgets-table-view
## 描述
渲染部件——表格、列表表格（分页）显示

## 注意事项
- 该小部件基于"qingbing/php-render"开发
- 对外提供widget组件
    - tableClass ： 选填，表格样式
    - tableId ： 选填，表格ID
    
    - summary ： 选填，表格标题摘要，不会再浏览器中显示，作用是增加表格的可读性，便于搜索引擎读懂表格
    
    - caption ： 选填，表格标题
    - captionId ： 选填，表格标题css-ID
    - captionClass ： 选填，表格标题css-class
    
    - header : 必填，表格头，里面有四个参数
        - label ： 必填，表头的文字
        - width ： 选填，列宽度
        - class ： 选填，列td的类
        - default ： 选填，当字段为null时显示的文字
        
    - data : 选填，html表格中显示的正式数据，每行记录要显示的数据的键对应于header的键
    - emptyString : 选填，当data为空时渲染的html
    
    - dataProcessing : 选填，callable，该回掉传入当行记录数据，返回修改或新增的键值数组以供表格渲染
    
    - pager : 选填，支持列表分页，分页信息存放属性
        - pageSize : 每页显示的数量
        - totalCount : 总共的记录的条数，配合pageSize一起类计算totalPage
        - totalPage : 总共的页数
        - pageVar : 页面表示的标记
        - pageList : 分页代码前后跳转的数量显示
        - lang : 页面显示的标签
## 使用方法
```php
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
```

## ====== 异常代码集合 ======

异常代码格式：1030 - XXX - XX （组件编号 - 文件编号 - 代码内异常）
```
 - 103000101 : Table显示组件表头不能为空
 
 - 103000201 : 没有指定总共显示的页数
 - 103000202 : 没有数据了！！！
```