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
            // print(__DIR__ . $dbpath);
            try {
                $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
                // $dbh = new PDO('sqlite:test.db', '', '', $options);
                $dbh = new \PDO('sqlite:' . __DIR__ . $dbpath, '', '', $options);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                exit;
            }
            try {
                $sql = '
                CREATE TABLE `Products` (
                    `id`	INTEGER NOT NULL,
                    `name`	TEXT NOT NULL UNIQUE,
                    `description`	TEXT,
                    `image_path`	TEXT NOT NULL,
                    `price`	INTEGER NOT NULL
                );
            ';
                $dbh->query($sql);
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
                exit;
            }
        }
        // else {
        //     // print(__DIR__ . $dbpath);
        //     try {
        //         $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
        //         $dbh = new PDO('sqlite:test.db', '', '', $options);
        //     } catch (PDOException $e) {
        //         echo 'Connection failed: ' . $e->getMessage();
        //         exit;
        //     }
        //     // $this->db_connector = sqlite_open(__DIR__ . $dbpath, 0666, $sqliteerror);
        // }
    }
}

// test
$db = new DBConnector('/db/db2.db');
