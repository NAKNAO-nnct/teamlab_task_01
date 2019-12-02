# Document

## 概要

商品データの登録・検索・変更・削除を行うAPI

* 商品画像
* 商品タイトル（最大100文字）
* 説明文（最大500文字）
* 価格

### 構成

* 言語: PHP7
* Freamwork: Slim4
* DB: SQLite3

## Endpoint

* @GET /api/products
* @POST /api/products
* @GET /api/products/:id
* @PUT /api/products/:id
* @DELETE /api/products/:id

### @GET /api/products

* 商品リストの取得  

#### パラメータ

| name      | Required | 詳細             |
| :-------- | :------- | :------------- |
| name      | No       | 商品タイトル（部分一致検索） |
| min_price | No       | 最高価格           |
| max_price | No       | 最低価格           |

**パラメータがない場合は商品リストを返却**

#### response

```json
{  
  "success": "'success' or 'failure'",
  "message": "'success' or 'error_message'",
  "details": [
    {
      "id": "商品ID",
      "name": "商品タイトル",
      "description": "商品説明",
      "image": "商品画像PATH",
      "price": "商品価格"
    }
  ],
  "details_url": "https://example.jp/response"
}
```

### @POST /api/products

* 商品の登録　　

#### パラメータ

* jsonで送る

| name        | Required | 詳細           |
| :---------- | :------- | :----------- |
| name        | Yes      | 商品タイトル       |
| description | Yes      | 商品説明文        |
| image       | Yes      | 商品画像（base64） |
| price       | Yes      | 商品価格         |

#### example

```json
{
  "name": "メチャスゴイオカネ",
  "description": "なんでも10円で買えてしまう画期的なおかね．22世紀より輸入．",
  "image": "data:image/jpeg;base64,/9j/4AAQSk...",
  "price": "1000"
}
```

### @GET /api/products/:id

* idの商品データの取得

#### response

```json
{  
  "success": "'success' or 'failure'",
  "message": "'success' or 'error_message'",
  "details": [
    {
      "id": "商品ID",
      "name": "商品タイトル",
      "description": "商品説明",
      "image": "商品画像PATH",
      "price": "商品価格"
    }
  ],
  "details_url": "https://example.jp/response"
}
```

### @PUT /api/products/:id

* idの商品データの更新

#### パラメータ

| name        | Required | 詳細           |
| :---------- | :------- | :----------- |
| name        | 更新したい時   | 商品タイトル       |
| description | 更新したい時   | 商品説明文        |
| image       | 更新したい時   | 商品画像（base64） |
| price       | 更新したい時   | 商品価格         |

```json
{
  "name": "商品タイトル",
  "description": "商品説明文",
  "image": "商品画像（base64）",
  "price": "商品価格"
}
```

#### Response

```json
{
  "success": "'success' or 'failure'",
  "message": "'success' or 'error_message'",
  "details_url": "https://example.jp/response"
}
```

### @DELETE /api/products/:id

* idの商品データの削除

#### Response

```json
{
  "success": "'success' or 'failure'",
  "message": "'success' or 'error_message'",
  "details_url": "https://example.jp/response"
}
```
