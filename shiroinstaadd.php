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
	//テーブルshirotagtableからカラムdateが変数$dateの値を一致する結果取得するSQL文の文字列を格納
	$sql = 'select * from shirotagtable where date like \''.$date.'%\'';
	//SQLを実行するための変数を生成
	$stmt = $dbh->prepare($sql);
	//SQLを実行
	$stmt->execute();
	//取得した行数を格納
	$rowcount = $stmt->rowCount();
	//SQLの結果の一番上の行を取得
	$rec = $stmt->fetch(PDO::FETCH_ASSOC);
	//$dateで表す日付のデータがない場合、また百名城のデータがすべてのデータがない場合、データを取得するようにする
	if(($rec == false) || ($rowcount < 100)){
		//テーブルshirotagtableからカラムdateが変数$dateの値を一致する行のデータを削除するSQL文の文字列を格納
	$sql = 'delete from shirotagtable where date like \''.$date.'%\'';
	//SQLを実行するための変数を生成
	$stmt = $dbh->prepare($sql);
	//SQLを実行
	$stmt->execute();
		//城の名前を格納した配列を生成
		$array = array("根室半島チャシ跡群","五稜郭","松前城","弘前城","根城","盛岡城","多賀城","仙台城","久保田城","山形城","二本松城","若松城","白河小峰城","水戸城","足利氏館","箕輪城","金山城","鉢形城","川越城","佐倉城","江戸城","八王子城","小田原城","武田氏館","甲府城","松代城","上田城","小諸城","松本城","高遠城","新発田城","春日山城","高岡城","七尾城","金沢城","丸岡城","一乗谷城","岩村城","岐阜城","山中城","駿府城","掛川城","犬山城","名古屋城","岡崎城","長篠城","伊賀上野城","松坂城","小谷城","彦根城","安土城","観音寺城","二条城","大阪城","千早城","竹田城","篠山城","明石城","姫路城","赤穂城","高取城","和歌山城","鳥取城","松江城","月山富田城","津和野城","津山城","備中松山城","鬼ノ城","岡山城","福山城","吉田郡山城","広島城","岩国城","萩城","徳島城","高松城","丸亀城","今治城","湯築城","松山城","大洲城","宇和島城","高知城","福岡城","大野城","名護屋城","吉野ヶ里遺跡","佐賀城","平戸城","島原城","熊本城","人吉城","大分府内城","岡城","飫肥城","鹿児島城","今帰仁城","中城城","首里城");
	//instagramのapiのアクセストークンを格納
	$access_token = "1546466429.b528a28.81831577fd6244249f0b0bd0a0ef1741";
	//ブロック表す変数(初期値は北海道・東北)
	$block = '北海道・東北';
		for($i = 0;$i < count($array);$i++){
			//$iが12以下ならブロックは北海道・東北
			if($i <= 12){
				$block = '北海道・東北';
			}
			//$iが13以上で22以下ならブロックは関東
			else if(($i >= 13) && ($i <= 22)){
				$block = '関東';
			}
			//$iが23以上で47以下ならブロックは東海・甲信越
			else if(($i >= 23) && ($i <= 47)){
				$block = '東海・甲信越';			
			}
			//$iが48以上で61以下ならブロックは近畿
			else if(($i >= 48) && ($i <= 61)){
				$block = '近畿';		
			}
			//$iが62以上で83以下ならブロックは近畿
			else if(($i >= 62) && ($i <= 83)){
				$block = '中国・四国';			
			}
			//$iが84以上ならブロックは九州・沖縄
			else if($i >= 84){
				$block = '九州・沖縄';			
			}
			echo "https://api.instagram.com/v1/tags/{$array[$i]}?access_token={$access_token}";
			//$arrayの$i番目の要素をタグとした情報をJSON形式としたデータを格納
			$obj = json_decode(@file_get_contents("https://api.instagram.com/v1/tags/{$array[$i]}?access_token={$access_token}"));
			//タグの投稿数を表す文字列を格納
			$count = $obj->data->media_count;
			//テーブルshirotagtableにカラムshironameに$arrayの$i番目の要素,カラムblockに$block,カラムtagcountに$count,カラムdateに$dateを挿入するSQL文の文字列を格納
		$sql = 'insert into shirotagtable (shironame,block,tagcount,date) values(\''.$array[$i].'\', \''.$block.'\','.$count.',\''.$date.'\')';
		//SQLを実行するための変数を生成
		$stmt = $dbh->prepare($sql);
		//SQLを実行
		if($stmt->execute()){
				//データが追加されたとき
				echo $i.'番目登録完了<br>';
			}else{
				//データが追加されなかったとき
				echo $i.'番目登録失敗<br>';
			}		
		}
	}
	//$dateを表す日付のデータがあった場合の処理
	else{
		echo 'すでに今日の分は登録';
	}
	$dbh = null;
}
catch(Exception $e){
	echo 'データベースに障害';
}
?>