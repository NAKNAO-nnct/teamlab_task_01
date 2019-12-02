<?php

namespace App\Models;

class DBConnector
{
    // コネクタ
    private $db_connector;

    // コネクタを開いてぶち込む
    public function __construct($dbpath)
    {
        // DBファイルがあるか否か
        $is_exists = file_exists(__DIR__ . $dbpath);

        // DBにアクセス
        try {
            $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);

            $this->db_connector = new \PDO('sqlite:' . __DIR__ . $dbpath, '', '', $options);
            $this->db_connector->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            /* 
                エラー処理をいれる
            */

            echo 'Connection failed: ' . $e->getMessage();
            exit;
        }

        // DBファイルの初期化作業
        if (!$is_exists) {
            try {

                $sql = explode(';', file_get_contents(__DIR__ . '/db/init.sql'));

                foreach ($sql as $init_query) {
                    $this->db_connector->query($init_query . ';');
                }
            } catch (PDOException $e) {
                echo 'Error: ' . $e->getMessage();
                exit;
            }
        }
    }

    // 取得系クエリ
    public function getQuery($sql_query)
    {
        $stmt =  $this->db_connector->query($sql_query);
        // $stmt->closeCursor();
        return $stmt;
    }

    // 更新系クエリ
    public function setQuery($sql_query)
    {
        $this->db_connector->exec($sql_query);
    }
}

// ファイル分けたらエラー吐く．謎
class DataAccessProducts
{
    private $db_conncector;

    public function __construct()
    {
        $this->db_conncector = new DBConnector('/db/db.db');
    }


    # # # # # # # #
    #    取得系    
    # # # # # # # #
    // データを取得し，整形する
    public function getQuery($query)
    {
        $res = $this->db_conncector->getQuery($query);
        $rows = array();
        foreach ($res as $row) {
            $rows[] = $this->formatResult($row);
        }
        return $rows;
    }

    // 結果クエリ整形
    public function formatResult($result)
    {
        $rows = array(
            'id' => $result['id'],
            'name' => $result['name'],
            'description' => $result['description'],
            'image' => $result['image'],
            'price' => $result['price']
        );
        return $rows;
    }

    // 全データを取得
    public function getAllData()
    {
        return $this->getQuery('SELECT * from Products;');
    }

    // idからデータを取得
    public function getDataFromIds($ids)
    {
        $sql_query = 'id = Null';
        foreach ($ids as $id) {
            $sql_query = $sql_query . ' OR id = ' . $id;
        }
        return $this->getQuery('SELECT * from Products WHERE ' . $sql_query . ';');
    }

    // AND検索
    public function searchData($param)
    {
        $sql_query = '';

        if (!empty($param['name']))
            $sql_query = $sql_query . " name like '%$param[name]%' ";


        if (!empty($param['max_price'])) {
            if (!($sql_query == ''))
                $sql_query = $sql_query . ' AND';
            $sql_query = $sql_query . " price <= $param[max_price] ";
        }

        if (!empty($param['min_price'])) {
            if (!($sql_query == ''))
                $sql_query = $sql_query . ' AND';
            $sql_query = $sql_query . " price >= $param[min_price] ";
        }
        return $this->getQuery('SELECT * from Products WHERE ' . $sql_query . ';');
    }


    # # # # # # # #
    #    更新系    
    # # # # # # # #
    // データの挿入
    public function insertQuery($data)
    {
        $query = "INSERT INTO Products values($data[id], '$data[name]', '$data[description]', '$data[image]', $data[price])";
        $this->db_conncector->setQuery($query);
    }

    // データの更新
    public function updateQuery($data)
    {
        // $this->db_conncector->setQuery($query);
        $query = "UPDATE Products set name = '$data[name]', description = '$data[description]', image = '$data[image]', price = $data[price] WHERE id = $data[id];";

        $this->db_conncector->setQuery($query);
    }

    // データの削除
    public function deteleQuery($id)
    {
        $query = "DELETE from Products WHERE id = ${id};";
        return $this->db_conncector->setQuery($query);
    }
}


if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    print("単体テスト");
    // test
    // $db = new DataAccessProducts();
    // $db->insertQuery(array(
    //     "id" => 11,
    //     "name" => "hoge",
    //     "description" => "hogehoge",
    //     "image" => "/images/hogehogehoge.png",
    //     "price" => 15000
    // ));
    // print_r($db->searchDataFromName("おかね"));
    // print_r($db->searchData(array("max" => 100, "name" => "おかね")));
    // print_r($db->getQuery('SELECT * from Products;'));
    // print_r($db->getQuery('SELECT * from Products where id = 4;'));
    // print_r($db->getAllData());
    // print_r($db->getData("SELECT * from Products where price > 100 and price < 16000;"));
    // print_r($db->getDataFromIds(array(1, 2, 3)));

    // $a = 4;
    // print($a *= 3);
}
