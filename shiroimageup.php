<?php
try{
	//百名城の名前を格納した配列を生成
	$array = array("根室半島チャシ跡群","五稜郭","松前城","弘前城","根城","盛岡城","多賀城","仙台城","久保田城","山形城","二本松城","若松城","白河小峰城","水戸城","足利氏館","箕輪城","金山城","鉢形城","川越城","佐倉城","江戸城","八王子城","小田原城","武田氏館","甲府城","松代城","上田城","小諸城","松本城","高遠城","新発田城","春日山城","高岡城","七尾城","金沢城","丸岡城","一乗谷城","岩村城","岐阜城","山中城","駿府城","掛川城","犬山城","名古屋城","岡崎城","長篠城","伊賀上野城","松坂城","小谷城","彦根城","安土城","観音寺城","二条城","大阪城","千早城","竹田城","篠山城","明石城","姫路城","赤穂城","高取城","和歌山城","鳥取城","松江城","月山富田城","津和野城","津山城","備中松山城","鬼ノ城","岡山城","福山城","吉田郡山城","広島城","岩国城","萩城","徳島城","高松城","丸亀城","今治城","湯築城","松山城","大洲城","宇和島城","高知城","福岡城","大野城","名護屋城","吉野ヶ里遺跡","佐賀城","平戸城","島原城","熊本城","人吉城","大分府内城","岡城","飫肥城","鹿児島城","今帰仁城","中城城","首里城");
	//instagramのapiのアクセストークンを格納
	$access_token = "1546466429.b528a28.81831577fd6244249f0b0bd0a0ef1741";
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
	$i = 0;
	//instagramに上がっている百名城のそれぞれの最新写真のURLを取りにいく繰り返し処理
	while($i < 100){
			$encode = urlencode($array[$i]);
			//$arrayの$i番目の要素をタグとした情報をJSON形式としたデータを格納
			$obj = json_decode(@file_get_contents('https://api.instagram.com/v1/tags/'.$encode.'?access_token='.$access_token));
			//ここのメディアの情報を表す配列を格納
			$imagearray = $obj->data;
			//メディアタイプの情報を表す文字列を格納
			$type = $imagearray[0]->type;
			//メディアタイプが写真であるか
			if(strcmp($type,'image') == 0){
				//画像のURLを表す文字列を格納
				$image = $imagearray[0]->images->thumbnail->url;
				//テーブルshironewimagetableのカラムshironameが$arrayの$i番目と一致する行のカラムimagenameを変数$imageにアップデートするSQL
				$sql = 'update shironewimagetable set imagename = \''.$image.'\' where shironame = \''.$array[$i].'\'';
				//SQLを実行するための変数を生成
				$stmt = $dbh->prepare($sql);
				//SQLを実行
				if($stmt->execute()){
					echo $i.$array[$i].'をアップデート<br>';
				}
				else{
					echo $i.$array[$i].'アップデート失敗<br>';
				}
			}else{
				echo $i.$array[$i].'は今画像でない<br>';
			}
		$i++;
		}
	$dbh = null;
}
catch(Exception $e){
	echo 'データベースに障害';
}
?>