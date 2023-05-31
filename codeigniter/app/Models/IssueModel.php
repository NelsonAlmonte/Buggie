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

    public function getIssues($project, $filters = ['fields' => [], 'limit' => 0, 'offset' => 0])
    {
        $issues = 'i.id, i.title, i.reporter AS reporter_id, i.assignee AS assignee_id, i.start_date,';
        $reporter = 'CONCAT(c_r.name, " ", c_r.last) AS reporter_name, c_r.username AS reporter,';
        $assignee = 'CONCAT(c_a.name, " ", c_a.last) AS assignee_name, c_a.username AS assignee,';
        $classification = 'c_cl.name AS classification_name, c_cl.color AS classification_color,';
        $severity = 'c_se.name AS severity_name, c_se.color AS severity_color,';
        $status = 'c_st.name AS status_name, c_st.color AS status_color, c_st.name AS status';
        $query = $issues . $reporter . $assignee . $classification . $severity . $status;
        return $this->db
            ->table('issues i')
            ->select($query)
            ->join('collaborators c_r', 'c_r.id = i.reporter')
            ->join('collaborators c_a', 'c_a.id = i.assignee', 'left')
            ->join('categories c_cl', 'c_cl.id = i.classification')
            ->join('categories c_se', 'c_se.id = i.severity')
            ->join('categories c_st', 'c_st.id = i.status')
            ->where('i.project', $project)
            ->havingLike($filters['fields'])
            ->limit($filters['limit'], $filters['offset'])
            ->orderBy('i.id DESC')
            ->get()
            ->getResultArray();
    }

    public function getIssue($id)
    {
        $issues = 'i.*, ';
        $reporter = 'CONCAT(c_r.name, " ", c_r.last) AS reporter_name,';
        $assignee = 'CONCAT(c_a.name, " ", c_a.last) AS assignee_name,';
        $classification = 'c_cl.name AS classification_name, c_cl.color AS classification_color,';
        $severity = 'c_se.name AS severity_name, c_se.color AS severity_color,';
        $status = 'c_st.name AS status_name, c_st.color AS status_color';
        $query = $issues . $reporter . $assignee . $classification . $severity . $status;
        return $this->db
            ->table('issues i')
            ->select($query)
            ->join('collaborators c_r', 'c_r.id = i.reporter')
            ->join('collaborators c_a', 'c_a.id = i.assignee', 'left')
            ->join('categories c_cl', 'c_cl.id = i.classification')
            ->join('categories c_se', 'c_se.id = i.severity')
            ->join('categories c_st', 'c_st.id = i.status')
            ->where('i.id', $id)
            ->get()
            ->getRowArray();
    }

    public function updateIssue($data)
    {
        return $this->db
            ->table('issues')
            ->where(['id' => $data['id']])
            ->update($data);
    }

    public function deleteIssue($id)
    {
        return $this->db
            ->table('issues')
            ->where('id', $id)
            ->delete();
    }

    public function getCollaboratorIssues($collaborator)
    {
        $issues = 'i.id, i.title, i.reporter AS reporter_id, i.assignee AS assignee_id, i.start_date,';
        $reporter = 'CONCAT(c_r.name, " ", c_r.last) AS reporter_name,';
        $assignee = 'CONCAT(c_a.name, " ", c_a.last) AS assignee_name,';
        $classification = 'c_cl.name AS classification_name, c_cl.color AS classification_color,';
        $severity = 'c_se.name AS severity_name, c_se.color AS severity_color,';
        $status = 'c_st.name AS status_name, c_st.color AS status_color, c_st.name AS status';
        $query = $issues . $reporter . $assignee . $classification . $severity . $status;
        return $this->db
            ->table('issues i')
            ->select($query)
            ->join('collaborators c_r', 'c_r.id = i.reporter')
            ->join('collaborators c_a', 'c_a.id = i.assignee', 'left')
            ->join('categories c_cl', 'c_cl.id = i.classification')
            ->join('categories c_se', 'c_se.id = i.severity')
            ->join('categories c_st', 'c_st.id = i.status')
            ->where('i.assignee', $collaborator)
            ->orderBy('i.id DESC')
            ->get()
            ->getResultArray();
    }
}
