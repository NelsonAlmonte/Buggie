<?php

namespace App\Cells\Shared\Pagination;

use CodeIgniter\View\Cells\Cell;

class Pagination extends Cell
{
    protected $url = '';
    protected $page = 1;
    protected $recordsPerPage = 5;
    protected $previousPage = 0;
    protected $nextPage = 2;

    public function mount()
    {
        helper('url');

        if (isset($_GET['page']) && $_GET['page'] != '') $this->page = $_GET['page'];
        $this->previousPage = $this->page - 1;
        $this->nextPage = $this->page + 1;

        $this->url = $this->_addQueryParamsToUrl(['page' => $this->page]);
    }

    private function _addQueryParamsToUrl($params)
    {
        $currentUrl = parse_url($_SERVER['REQUEST_URI']);
        $queryParams = [];
        
        if (!empty($currentUrl['query'])) parse_str($currentUrl['query'], $queryParams);
        foreach ($params as $key => $param) $queryParams[$key] = $param;
        $newUrl = $currentUrl['path'] . '?' . http_build_query($queryParams);
        
        return $newUrl;
    }

    public function render(): string
    {
        $data['url'] = $this->url;
        return $this->view('pagination_cell', $data);
    }
}
