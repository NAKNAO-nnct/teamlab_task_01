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

class DBConnector
{
    // コネクタ
    private $db_connector;

    // コネクタを開いてぶち込む
    public function __construct($dbpath)
    {
        // DBファイルがなければテーブルを作る
        if (!file_exists(__DIR__ . $dbpath)) {
            $this->db_connector = sqlite_open($dbpath, 0666, $sqliteerror);
            $sql = '
                CREATE TABLE `Products` (
                    `id`	INTEGER NOT NULL,
                    `name`	TEXT NOT NULL UNIQUE,
                    `description`	TEXT,
                    `image_path`	TEXT NOT NULL,
                    `price`	INTEGER NOT NULL
                );
            ';

            $result_flag = sqlite_query($this->db_connector, $sql, SQLITE_BOTH, $sqliteerror);
        } else {
            $this->db_connector = sqlite_open($dbpath, 0666, $sqliteerror);
        }
    }

    // コネクタを閉じる
    function __destruct()
    {
        sqlite_close($this->db_connector);
    }
}

// test
$db = DBConnector('/db/db.db');
