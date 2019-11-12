<?php

namespace App\Models;

class Products
{
    public $db_date;
    public function __construct()
    {
        // $this->db_date = json_decode(file_get_contents("/Users/NakanoYuki/Projects/teamlab/teamlab_task_01/api_server/src/Models/db/db.json"), true);
        // $this->db_date = json_decode(file_get_contents(__DIR__ . '/db/db.json'), true);
        // return $this->db_date;
    }

    public function getDate()
    {
        // return $this->db_date;
        $this->db_date = json_decode(file_get_contents(__DIR__ . '/db/db.json'), true);
        return $this->db_date;
    }
}
