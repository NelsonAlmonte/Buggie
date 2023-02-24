<?php

namespace App\Models;

use CodeIgniter\Model;

class IssueModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveIssue($data)
    {
        $this->db
            ->table('issues')
            ->insert($data);
        return $this->db->insertID();
    }

    public function getIssues($project)
    {
        $reporter = 'CONCAT(c_r.name, " ", c_r.last) AS reporter_name,';
        $assignee = 'CONCAT(c_a.name, " ", c_a.last) AS assignee_name,';
        $classification = 'c_cl.name AS classification_name, c_cl.color AS classification_color,';
        $severity = 'c_se.name AS severity_name, c_se.color AS severity_color,';
        $status = 'c_st.name AS status_name, c_st.color AS status_color';
        $query = $reporter . $assignee . $classification . $severity . $status;
        return $this->db
            ->table('issues i')
            ->select('i.*, ' . $query)
            ->join('collaborators c_r', 'c_r.id = i.reporter')
            ->join('collaborators c_a', 'c_a.id = i.assignee', 'left')
            ->join('categories c_cl', 'c_cl.id = i.classification')
            ->join('categories c_se', 'c_se.id = i.severity')
            ->join('categories c_st', 'c_st.id = i.status')
            ->where('i.project', $project)
            ->orderBy('i.id DESC')
            ->get()
            ->getResultArray();
    }
}
