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
	//配列の初期化
	$array = array();
	//順位つけるために使われる変数
	$i = 0;
	//過去の日付のデータをとるために使われる変数
	$dateday = 0;
	//城の情報を取得する繰り返し処理を実行
	while(1){
		//テーブルshirotagtableのdateが変数$dateの値、テーブルshirotagtableとshironewimagetableのshironameが一致し、shirotagtableのtagcountの値が大きい順にテーブルshirotagtableのshironame,tagcount,blockとshironewimagetableのimagenameを取得するSQL文の文字列を格納
		$sql = 'select shirotagtable.shironame, shirotagtable.tagcount, shirotagtable.block, shironewimagetable.imagename
from shirotagtable, shironewimagetable where shirotagtable.shironame = shironewimagetable.shironame
and shirotagtable.date like \''.$date.'%\' order by shirotagtable.tagcount desc';
		//SQLを実行するための変数を生成
		$stmt = $dbh->prepare($sql);
		//SQLを実行
		$stmt->execute();
		//取得した行数を格納
		$rowcount = $stmt->rowCount();
		//SQLの結果の一番上の行を取得
		$rec = $stmt->fetch(PDO::FETCH_ASSOC);
		if(($rec == false) || ($rowcount < 100)){
			//$recがfalseまた取得した行数が100より小さい場合の処理
			//変数$datedayをデクリメント
			$dateday--;
			//変数$dateに$dateday前の日付を格納
			$date = date('Y-m-d',strtotime($dateday.' day'));
		}
		else{
			//$recがtrueかつ取得した行数が100より小さくない場合の処理
			break;
			}
	}
	//json形式で出力するために使われる配列を生成のループ処理
	while(1){
		//変数$recがfalseならループ処理終了
		if($rec == false){
			break;
		}
		$i++;
		//$arrayのi番目の要素にキーrankに$i,キーshironameに$rec['shironame'],キーtagcountに$rec['tagcount'],キーblockに$rec['block'],キーimagenameに$rec['imagename']をひもづけたものを格納
		$array[] = array('rank'=>$i,'shironame'=>$rec['shironame'],'tagcount'=>$rec['tagcount'],'block'=>$rec['block'],'imagename'=>$rec['imagename']);
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