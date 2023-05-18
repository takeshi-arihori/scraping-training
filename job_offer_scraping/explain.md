# 作成手順

## 1. 環境設定

### Dockerを起動(インストール)

### Laravelのインストール

参照サイト: https://laravel.com/docs/10.x/installation#getting-started-on-macos

```
curl -s "https://laravel.build/job_offer_scraping" | bash
```


### Laravelの文字化け対策
参照サイト: https://qiita.com/KyuKyu/items/efdba5d838d16e016613

#### 環境設定ファイルの編集
```
[mysqld]
character-set-server = utf8mb4
collation-server = utf8mb4_general_ci

[client]
default-character-set=utf8mb4
```

### docker.conpose.ymlの編集
```
    mysql:
        volumes:
            - 'sail-mysql:/var/lib/mysql'
            - './vendor/laravel/sail/database/mysql/create-testing-database.sh:/docker-entrypoint-initdb.d/10-create-testing-database.sh'
            - ./my.cnf:/etc/mysql/conf.d/my.cnf <- 仮想環境側にコピーするためのパスを書く
```


### database.phpの編集
```<br>
        'mysql' => [
            'collation' => 'utf8mb4_general_ci', <- ここを編集
```

### 編集したため再度dockerをbuild
```
./vendor/bin/sail up -d --build
```

### laravel-goutteのインストール
参照サイト: https://github.com/dweidner/laravel-goutte


### スクレイピング用のコマンドを作成
#### Artisanコマンドの作成
```
./vendor/bin/sail php artisan make:command ScrapeMynavi
```

### コマンドのリストの確認
```
./vendor/bin/sail php artisan list
```


### コマンドの実行テスト
```
 ./vendor/bin/sail artisan scrape:mynavi
```

###


## 2. 対象のサイトの調査
``robots.txt``でサイトのクローリングの許可を確認する。
