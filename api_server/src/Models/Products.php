<?php

namespace App\Models;

class Products
{
    public $db_data;
    private $db_file = '/db/db.json';

    public function __construct()
    {
        $this->db_data = json_decode(file_get_contents(__DIR__ . $this->db_file), true);
    }


    // データをDBに保存
    public function saveData($data)
    {
        return file_put_contents(__DIR__ . $this->db_file, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }

    // データの取得
    public function getData()
    {
        return $this->db_data;
    }

    // データのセット
    public function setData($data)
    {
        $this->data = $data;
        $this->saveData($data);
        return;
    }
}

//  test
// $na = new Products();
// $da = $na->getData();
// $at = array(
//     "10" => array(
//         "name" => "bokete"
//     )
// );
// $at = array_merge($da, array("11" => array("name" => "gomi")));
// var_dump($at);
// echo "\n";
// $na->addData($at);
