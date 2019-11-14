<?php

namespace App\Models;

class Products
{
    public $db_data;
    private $db_file = '/db/db.json';
    public function __construct()
    {
        // $this->db_date = json_decode(file_get_contents("/Users/NakanoYuki/Projects/teamlab/teamlab_task_01/api_server/src/Models/db/db.json"), true);
        // $this->db_date = json_decode(file_get_contents(__DIR__ . '/db/db.json'), true);
        // return $this->db_date;
    }

    // データの取得
    public function getData()
    {
        // return $this->db_date;
        $this->db_data = json_decode(file_get_contents(__DIR__ . $this->db_file), true);
        return $this->db_data;
    }

    // データの追加
    public function addData()
    {
        file_put_contents(__DIR__ . $this->db_file, json_encode($this->db_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        // $db = fopen(__DIR__ . $this->db_file, 'w');
        // fwrite($db, json_encode($this->db_data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        // fclose($db);
    }
}
