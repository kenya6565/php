<?php
date_default_timezone_set('Asia/Tokyo');
// データベースの接続情報
define( 'DB_HOST', 'mysql');
define( 'DB_USER', 'ken');
define( 'DB_PASS', 'Nanryou1');
define( 'DB_NAME', 'php');

// 変数の初期化
$csv_data = null;
$sql = null;
$res = null;
$message_array = array();

session_start();
//もし管理者がアクセスダウンロードボタンを押してた場合はOK
if( !empty($_SESSION['admin_login']) && $_SESSION['admin_login'] === true ) {

	// ①CSV出力の設定
  header("Content-Type: application/octet-stream");
  //ファイル名の設定
  header("Content-Disposition: attachment; filename=メッセージデータ.csv");
  //エンコード
	header("Content-Transfer-Encoding: binary");

  // ②データベースに接続
	$mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);
	
	// 接続エラーの確認
	if( !$mysqli->connect_errno ) {

		$sql = "SELECT * FROM board ORDER BY post_date ASC";
		$res = $mysqli->query($sql);

		if( $res ) {
			$message_array = $res->fetch_all(MYSQLI_ASSOC);
		}

		$mysqli->close();
  }
  //var_dump($message_array);
  // ③CSVデータを作成
  // もし②が実行されていたらここも動く
	if( !empty($message_array) ) {

		// 1行目のラベル作成
		$csv_data .= '"ID","表示名","メッセージ","投稿日時"'."\n";

		foreach( $message_array as $value ) {

			// データを1行ずつCSVファイルに書き込む
			$csv_data .= '"' . $value['id'] . '","' . $value['view_name'] . '","' . $value['message'] . '","' . $value['post_date'] . "\"\n";
		}
	}
  
  // ④ファイルを出力	
  echo $csv_data;
  //var_dump($csv_data);
  
} else {

	// ログインページへリダイレクト
	header("Location: ./admin.php");
}

//ページとして表示しないときはreturnでPHPを終了させる
return;