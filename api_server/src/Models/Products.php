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
                $this->db_connector = new \PDO('sqlite:' . __DIR__ . $dbpath, '', '', $options);
                $this->db_connector->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                exit;
            }
            try {

                $sql = explode(';', file_get_contents(__DIR__ . '/db/init.sql'));

                foreach($sql as $init_query){
                    $this->db_connector->query($init_query.';');
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
                exit;
            }
        }
        else {
            // print(__DIR__ . $dbpath);
            try {
                $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
                $this->db_connector = new \PDO('sqlite:' . __DIR__ . $dbpath, '', '', $options);
            } catch (PDOException $e) {
                echo 'Connection failed: ' . $e->getMessage();
                exit;
            }
        }
    }

    // 取得系クエリ
    public function getQuery($sql_query){
        $stmt = $this->db_connector->query($sql_query);
        $rows = array();
        $count = 0;
        foreach ($stmt as $row) {
            $rows[] = $this->formatResult($row);
        }
        $stmt->closeCursor();
        return $rows;
    }

    // 結果クエリ整形
    public function formatResult($result) {
        $rows = array(
            'id'=> $result['id'],
            'name'=>$result['name'],
            'description'=>$result['description'],
            'image_path'=>$result['image_path'],
            'price' => $result['price']
        );
        return $rows;
    }

}

// test
$db = new DBConnector('/db/db.db');
print_r($db->getQuery('SELECT * from Products;'));
