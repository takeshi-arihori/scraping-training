# スクレイピングの練習

## 流れ

1. AWSにサーバー立てる(EC2)
2. Docker環境設定
3. スクレイピングするためのプログラムを書く(Python)
4. 取得したデータをDB格納する
5. 上記の処理の自動化のためにシェルスクリプト書いて実装
6. UI構築(データをグラフで表示)



## 1. AWS EC2インスタンスの作成
まずは、AWSのマネジメントコンソールから新しいEC2インスタンスを作成します。ここではAmazon Linux 2 AMIを選択します。インスタンスタイプは、このスクリプトを実行するのに十分なリソースを持つものを選択します。セキュリティグループでは、SSHとHTTP（あるいはHTTPS）のポートを開放します。


## 2. DockerとDocker Composeのインストール
次に、SSHを使ってEC2インスタンスに接続し、DockerとDocker Composeをインストールします。

```
sudo yum update -y
sudo yum install -y docker
sudo service docker start
sudo usermod -a -G docker ec2-user

```

Docker Composeをインストールします。
```
sudo curl -L "https://github.com/docker/compose/releases/download/1.29.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose

```

## 3. PythonスクリプトとDockerfileの作成
次に、PythonスクリプトとDockerfileを作成します。Pythonスクリプトでは、BeautifulSoupなどのライブラリを使用してSEOキーワードのスクレイピングを行います。また、スクレイピングしたデータはMySQLに格納します。

以下はDockerfileのサンプルです。

```
FROM python:3.9

WORKDIR /app

COPY requirements.txt .
RUN pip install -r requirements.txt

COPY . .

CMD ["python", "your_script.py"]
```

そして、requirements.txtファイルには使用するPythonライブラリを記述します。


```
beautifulsoup4==4.9.3
requests==2.25.1
mysql-connector-python==8.0.26
```


## 4. Docker Composeファイルの作成
Docker Composeファイルを作成します。ここではPythonスクリプトを実行するサービスと、MySQLのサービスの2つを定義します。

以下はdocker-compose.ymlのサンプルです。


```
version: '3'
services:
  app:
    build: .
    depends_on:
      - db
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: yourpassword
      MYSQL_DATABASE: yourdatabase
```


## 5. シェルスクリプトとCronの設定
最後に、Pythonスクリプトを定期的に実行するためのシェルスクリプトを作成し、Cronにそのスクリプトを登録します。

以下はシェルスクリプトのサンプルです。このスクリプトでは、Docker Composeを使ってPythonスクリプトを実行します。

```
#!/bin/bash

cd /path/to/your/project
/usr/local/bin/docker-compose up
```

このシェルスクリプトに実行権限を付与します。

```
chmod +x your_script.sh
```

最後に、Cronにこのシェルスクリプトを登録します。以下のコマンドでCronの設定を開きます。

```
crontab -e
```

そして、以下のように設定します。この設定では、シェルスクリプトが毎日0時に実行されます。

```
0 0 * * * /path/to/your_script.sh
```



## キーワードのパフォーマンス追跡のための具体的な手順

以下のようなステップを想定することができます。

1: データの収集

まずは、特定のキーワードについてGoogle検索結果の最初のページ（上位約10件）をスクレイピングします。このとき、各結果のURL、タイトル、メタディスクリプションなどを取得します。


2: データの格納

取得したデータをデータベースに格納します。ここでは各キーワード、URL、取得日時、タイトル、メタディスクリプションをフィールドとするテーブルを想定します。これにより、時間の経過とともにキーワードのパフォーマンスを追跡することができます。


3: パフォーマンスの評価

定期的に（例えば週に一度）データを取得し、それぞれのキーワードについてその時点でのGoogle検索結果の順位を確認します。順位の変動はキーワードのパフォーマンスを評価する一つの指標となります。


4: データのビジュアライゼーション

データベースから取得したデータをもとに、キーワードのパフォーマンスを時間の経過とともにグラフで表現します。例えば、横軸に時間、縦軸にGoogle検索結果の順位とする折れ線グラフなどが考えられます。


5: アクションの決定

グラフから、キーワードのパフォーマンスが向上しているか、または低下しているかを確認します。この情報をもとに、必要に応じてSEO戦略の見直しやキーワードの変更などを行います。
以上のような手順で、特定のキーワードが時間の経過とともにどのようにパフォーマンスを発揮しているのかを追跡し、あなたのSEO戦略が効果的であるかどうかを評価することが可能になります。
