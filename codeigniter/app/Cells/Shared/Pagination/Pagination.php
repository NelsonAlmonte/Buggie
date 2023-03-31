<?php

namespace App\Cells\Shared\Pagination;

use CodeIgniter\View\Cells\Cell;

class Pagination extends Cell
{
    protected $url = ['previousPage' => '', 'nextPage' => ''];
    protected $page = 1;
    protected $previousPage = 0;
    protected $nextPage = 2;
    protected $currentRecords = 0;

    public function mount($currentRecords)
    {
        if (isset($_GET['page']) && $_GET['page'] != '') $this->page = $_GET['page'];
        $this->previousPage = $this->page - 1;
        $this->nextPage = $this->page + 1;
        $this->currentRecords = $currentRecords;

        $this->url = [
            'previousPage' => $this->_addQueryParamsToUrl(['page' => $this->previousPage]),
            'nextPage' => $this->_addQueryParamsToUrl(['page' => $this->nextPage]),
        ];
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
        $data = [
            'url' => $this->url,
            'previousPage' => $this->previousPage,
            'currentRecords' => $this->currentRecords,
        ];
        
        return $this->view('pagination_cell', $data);
    }
}
