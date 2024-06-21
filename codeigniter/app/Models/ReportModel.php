<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getReportByCollaborator($type, $project = '')
    {
        return $this->db
            ->table('issues i')
            ->select('CONCAT(c.name, " ", c.last) AS label, COUNT(i.id) AS data')
            ->join('projects p', 'p.id = i.project')
            ->join('collaborators c', "c.id = i.$type")
            ->where('p.id', $project)
            ->groupBy('c.id')
            ->get()
            ->getResultArray();
    }

    public function getReportByCategoryType($type, $project = '')
    {
        return $this->db
            ->table('issues i')
            ->select('c.name AS label, COUNT(i.id) AS data')
            ->join('projects p', 'p.id = i.project')
            ->join('categories c', "c.id = i.$type")
            ->where('p.id', $project)
            ->groupBy('c.id')
            ->get()
            ->getResultArray();
    }
}
