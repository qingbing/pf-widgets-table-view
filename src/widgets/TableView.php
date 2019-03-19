<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-01-31
 * Version      :   1.0
 */

namespace Widgets;


use Abstracts\Model;
use Abstracts\OutputProcessor;
use Helper\Exception;
use Helper\Unit;

class TableView extends OutputProcessor
{
    /* @var string table表格的css类 */
    public $tableClass = 'table table-hover table-bordered table-striped';
    /* @var string table表格的ID名 */
    public $tableId = '';
    /* @var string 标题摘要，不会再浏览器中显示，作用是增加表格的可读性，便于搜索引擎读懂表格 */
    public $summary;

    /**
     * 表头配置
     * [
     *  'id' => ['label' => 'ID', 'class' => 'text-center', 'width' => '200px', 'default' => '***',],
     *  'username' => ['label' => '用户名'],
     *  'sex' => ['label' => '性别'],
     * ]
     * @var array
     */
    public $header;

    /* @var string 表格标题 */
    public $caption;
    /* @var string 标题css-ID */
    public $captionId;
    /* @var string 标题css-class */
    public $captionClass;

    /* @var array 渲染的 table-body 的数据数组 */
    public $data;
    /* @var string 当 table-body 数据数组为空时的提示 */
//    public $emptyString = '<p class="text-center" style="height:88px;line-height:88px;"><i class="fa fa-frown-o"></i>Sorry, there is no data to be found!</p>';
    public $emptyString = '<p class="text-center" style="height:88px;line-height:88px;"><i class="fa fa-frown-o"></i> 不好意思，暂时没有数据内容哦！</p>';

    /* @var string|array 若为分页表格，分页的具体信息 */
    public $pager;

    /* @var callable|null 数据回调处理 */
    public $dataProcessing;

    /**
     * 组件自动调用
     * @throws Exception
     */
    public function init()
    {
        if (empty($this->header)) {
            throw new Exception('Table显示组件表头不能为空', 103000101);
        }
    }

    /**
     * 运行组件
     */
    public function run()
    {
        $table = '<table';
        if (!empty($this->tableId)) {
            $table .= ' id="' . $this->tableId . '"';
        }
        if (!empty($this->tableClass)) {
            $table .= ' class="' . $this->tableClass . '"';
        }
        if (!empty($this->summary)) {
            $table .= ' summary="' . \Html::encode($this->summary) . '"';
        }
        $table .= '>';
        echo <<<EDO
{$table}{$this->rendHeader()}{$this->rendCaption()}{$this->rendBody()}{$this->rendFooter()}</table>
EDO;
    }

    /**
     * 获取渲染 table-caption 的html
     * @return string|null
     */
    protected function rendCaption()
    {
        if (empty($this->caption)) {
            return null;
        }
        $caption = '<caption';
        if (!empty($this->captionId)) {
            $caption .= ' id="' . $this->captionId . '"';
        }
        if (!empty($this->captionClass)) {
            $caption .= ' class="' . $this->captionClass . '"';
        }
        $caption .= '</caption>';
        return $caption;
    }

    /**
     * 获取渲染 table-head 的html
     * @return string
     */
    protected function rendHeader()
    {
        $rString = "\n<thead><tr>\n";
        foreach ($this->header as $config) {
            $_propertyString = ' class="text-center"';
            if (isset($config['width'])) {
                $_propertyString .= ' width="' . $config['width'] . '"';
            }
            $rString .= "<th{$_propertyString}>{$config['label']}</th>";
        }
        $rString .= "\n</tr></thead>\n";
        return $rString;
    }

    /**
     * 获取渲染 table-body 的html
     * @return string
     */
    protected function rendBody()
    {
        if (empty($this->data)) {
            $count = count($this->header);
            return "<tbody><tr>\n<td colspan=\"{$count}\">{$this->emptyString}</td>\n</tr></tbody>\n";
        }
        $body = [];
        foreach ($this->data as $data) {
            if (is_callable($this->dataProcessing)) {
                $process = call_user_func_array($this->dataProcessing, [$data]);
                if (!is_array($process)) {
                    $process = [];
                }
                if ($data instanceof Model) {
                    $data = array_merge($data->getAttributes(), $process);
                } else {
                    $data = array_merge($data, $process);
                }
            } elseif ($data instanceof Model) {
                $data = $data->getAttributes();
            }

            $rString = "\n<tr>";
            foreach ($this->header as $key => $config) {
                $_propertyString = '';
                if (isset($config['class'])) {
                    $_propertyString .= ' class="' . $config['class'] . '"';
                }

                if (isset($data[$key])) {
                    $rString .= "<td{$_propertyString}>{$data[$key]}</td>";
                } else if (isset($config['default'])) {
                    $rString .= "<td{$_propertyString}>{$config['default']}</td>";
                } else {
                    $rString .= "<td{$_propertyString}> - </td>";
                }
            }
            $rString .= "</tr>";
            array_push($body, $rString);
        }
        return '<tbody>' . implode('', $body) . "\n</tbody>\n";
    }

    /**
     * 获取渲染 table-footer 的html
     * @return string|null
     * @throws Exception
     * @throws \ReflectionException
     */
    protected function rendFooter()
    {
        if (empty($this->pager)) {
            return null;
        }
        if (is_string($this->pager)) {
            $pageCoding = $this->pager;
        } else {
            $info = [];
            if (is_array($this->pager)) {
                isset($this->pager['pageSize']) && $info['pageSize'] = $this->pager['pageSize'];
                isset($this->pager['totalCount']) && $info['totalCount'] = $this->pager['totalCount'];
                isset($this->pager['totalPage']) && $info['totalPage'] = $this->pager['totalPage'];
                isset($this->pager['pageVar']) && $info['pageVar'] = $this->pager['pageVar'];
                isset($this->pager['pageList']) && $info['pageList'] = $this->pager['pageList'];
                isset($this->pager['lang']) && $info['lang'] = $this->pager['lang'];
            } else {
                isset($this->pager->pageSize) && $info['pageSize'] = $this->pager->pageSize;
                isset($this->pager->totalCount) && $info['totalCount'] = $this->pager->totalCount;
                isset($this->pager->totalPage) && $info['totalPage'] = $this->pager->totalPage;
                isset($this->pager->pageVar) && $info['pageVar'] = $this->pager->pageVar;
                isset($this->pager->pageList) && $info['pageList'] = $this->pager->pageList;
                isset($this->pager->lang) && $info['lang'] = $this->pager->lang;
            }
            $pager = Unit::createObject(array_merge([
                'class' => '\TableViewSupports\PageCoding',
            ], $info));
            $pageCoding = $pager->paging();
        }
        if (empty($pageCoding)) {
            return null;
        } else {
            $count = count($this->header);
            return "<tfoot><tr>\n<td colspan=\"{$count}\">{$pageCoding}</td>\n</tr></tfoot>\n";
        }
    }
}