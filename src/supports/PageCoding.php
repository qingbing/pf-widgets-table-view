<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-02-01
 * Version      :   1.0
 */

namespace TableViewSupports;


use Abstracts\Base;
use Helper\Exception;

class PageCoding extends Base
{
    /* @var int 每页显示的条数 */
    public $pageSize = 10;
    /* @var int 总的数据条数 */
    public $totalCount;
    /* @var int 总页数 */
    public $totalPage;

    /* @var string 页面标签 */
    public $pageVar = 'page';

    /* @var int 显示的分页项目个数 */
    public $pageList = 3;

    /* @var array 分页标签 */
    public $lang = [
        'firstPage' => '<<',
        'prePage' => '＜',
        'nextPage' => '＞',
        'lastPage' => '>>',
    ];

    /* @var int 显示的当前页 */
    public $curPage;
    /* @var array 当前页面参数 */
    protected $params = [];
    /* @var string 当前页面路由 */
    protected $route;
    /* @var \web\Application */
    protected $app;

    /**
     * @throws \Exception
     */
    public function init()
    {
        if (null !== $this->totalCount && !empty($this->pageSize)) {
            $this->totalPage = intval(ceil($this->totalCount / $this->pageSize));
        }
        if (null === $this->totalPage) {
            throw new  Exception('没有指定总共显示的页数', 103000201);
        }
        $this->app = \PF::app();
        $this->route = $this->app->getUrlManager()->parseUrl();
        $this->params = $this->app->getRequest()->getParams();

        if (null === $this->curPage) {
            $this->curPage = isset($this->params[$this->pageVar]) ? intval($this->params[$this->pageVar]) : 1;
        }
        if ($this->curPage != 1 && $this->curPage > $this->totalPage) {
            throw new  Exception('没有数据了！！！', 103000202);
        }
    }

    /**
     * 创建URL
     * @param int $page
     * @return string
     * @throws \Exception
     */
    protected function createUrl($page = 1)
    {
        return $this->app->getUrlManager()->createUrl($this->route, array_merge($this->params, [
            $this->pageVar => $page,
        ]));
    }

    /**
     * 构建分页选项 list
     * @param int $start
     * @param int $end
     * @return string
     * @throws \Exception
     */
    protected function generateListPaging($start, $end)
    {
        $pagingCode = '';
        for ($i = $start; $i <= $end; $i++) {
            $pagingCode .= '<li><a href="' . $this->createUrl($i) . '">' . $i . '</a></li>' . "\n";
        }
        return $pagingCode;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function paging()
    {
        if ($this->totalPage <= 1) {
            return '';
        }
        $lang = $this->lang;
        $pagingCode = '<ul class="pagination">';
        // Generate front paging.
        if ($this->curPage > 2) {
            $pagingCode .= '<li><a href="' . $this->createUrl(1) . '">' . $lang['firstPage'] . '</a></li>' . "\n";
        }
        if ($this->curPage > 1) {
            $pagingCode .= '<li><a href="' . $this->createUrl($this->curPage - 1) . '">' . $lang['prePage'] . '</a></li>' . "\n";
        }
        if ($this->curPage - $this->pageList > 1) {
            $pagingCode .= '<li class="disabled"><span>...</span></li>' . "\n";
        }
        if ($this->curPage <= $this->pageList) {
            $pagingCode .= $this->generateListPaging(1, $this->curPage - 1);
        } else {
            $pagingCode .= $this->generateListPaging($this->curPage - $this->pageList, $this->curPage - 1);
        }
        // Generate current paging code.
        $pagingCode .= '<li class="active"><a href="javascript:void(0)">' . $this->curPage . '</a></li>' . "\n";
        // Generate back paging.
        if ($this->totalPage - $this->curPage > $this->pageList) {
            $pagingCode .= $this->generateListPaging($this->curPage + 1, $this->curPage + $this->pageList);
        } else {
            $pagingCode .= $this->generateListPaging($this->curPage + 1, $this->totalPage);
        }
        if ($this->totalPage - $this->curPage - $this->pageList > 0) {
            $pagingCode .= '<li class="disabled"><span>...</span></li>' . "\n";
        }
        if ($this->totalPage - $this->curPage > 0) {
            $pagingCode .= '<li><a href="' . $this->createUrl($this->curPage + 1) . '">' . $lang['nextPage'] . '</a></li>' . "\n";
        }
        if ($this->totalPage - $this->curPage > 1) {
            $pagingCode .= '<li><a href="' . $this->createUrl($this->totalPage) . '">' . $lang['lastPage'] . '</a></li>' . "\n";
        }
        $pagingCode .= '</ul>';
        return $pagingCode;
    }
}