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
	$date = date('Y-m-d');
	//過去の日付をとるために使われる変数
	$dateday = 0;
	//配列の初期化
	$array = array();
	
	while(1){
		//テーブルshirotagtableからカラムdateが変数$dateの値を一致しtagcountの大きい順にshironame,tagcount,blockを取得するSQL文の文字列を格納
		$sql = 'select shironame, tagcount, block 
from shirotagtable where date like \''.$date.'%\' order by tagcount desc';
		//SQLを実行するための変数を生成
		$stmt = $dbh->prepare($sql);
		//SQLを実行
		$stmt->execute();
		//取得した行数を格納
		$rowcount = $stmt->rowCount();
		//SQLの結果の一番上の行を取得
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		if(($rec == false) || ($rowcount < 100)){
			//変数$dateで表す日付のデータがない場合また取得した行数が100より小さい場合,$dateの値を前日を表す日付にする
			$dateday--;
			$date = date('Y-m-d',strtotime($dateday.' day'));
		}
		else{
			//変数$dateで表す日付のデータがある場合かつ取得した行数が100より小さくない場合,ループ処理を終了
			break;
		}
	}
	//城の情報をJSON形式で出力するために使われる配列の生成のループ処理
	while(1){
		//$recの値がfalseの場合ループ処理を終了
		if($rec == false){
			break;
		}
		//$arrayの末尾に変数$recを追加
		$array[] = $rec;
		//SQLの結果の一番上の行を取得
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	}
	//配列をもとにJSON形式で出力
	echo json_encode($array);
	//データベースとの接続を切断
	$dbh = null;
}
catch(Exception $e){
	echo 'データベースに障害';
}
?>