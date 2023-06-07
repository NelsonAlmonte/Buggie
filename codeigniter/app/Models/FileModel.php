<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function saveFile($data)
    {
        return $this->db
            ->table('files')
            ->insert($data);
    }

    public function getIssueFiles($issue)
    {
        return $this->db
            ->table('files f')
            ->select('f.*, c.name AS collaborator_name, c.last, c.image')
            ->join('collaborators c', 'c.id = f.collaborator')
            ->where('f.issue', $issue)
            ->get()
            ->getResultArray();
    }

    public function deleteFile($id)
    {
        return $this->db
            ->table('files')
            ->where('id', $id)
            ->delete();
    }

    public function getProjectFiles($id)
    {
        return $this->db
            ->table('files f')
            ->select('f.*, i.title, i.id AS issue_id, c.name AS collaborator_name, c.last, c.image')
            ->join('issues i', 'i.id = f.issue')
            ->join('projects p', 'p.id = i.project')
            ->join('collaborators c', 'c.id = f.collaborator')
            ->where('p.id', $id)
            ->get()
            ->getResultArray();
    }
}
