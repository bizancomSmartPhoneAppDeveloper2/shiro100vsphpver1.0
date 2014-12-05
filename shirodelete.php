<?php
try{
	//時間を東京の時間に指定
	date_default_timezone_set('Asia/Tokyo');
	//データベース名とホスト名の情報を格納
	$dsn = 'mysql:dbname=u551671545_shiro;host=mysql.miraiserver.com';
	//データベースのユーザー名
	$usr = 'u551671545_shiro';
	//データベースのパスワード
	$password ='bizan07';
	//データベースと接続する変数を生成
	$dbh = new PDO($dsn,$usr,$password);
	//使う文字コードをutf8に設定
	$dbh->query('SET NAMES utf8');
	//現在の年-月-日と並んだ文字列
	$date = date('Y-m-d', strtotime('-14 day'));
	echo '2週間前は'.$date;
	//テーブルshirotagtableからカラムdateが変数$dateと一致する値の行を削除するsql
	$sql = 'delete from shirotagtable where date like \''.$date.'%\'';
	//SQLを実行するための変数を生成
	$stmt = $dbh->prepare($sql);
	//SQLを実行
	if($stmt->execute()){
		echo '<br>削除成功';
	}else{
		echo '<br>削除失敗';
	}
	$dbh = null;
}
catch(Exception $e){
	echo 'データベースに障害';
}
?>