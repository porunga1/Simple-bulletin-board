<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>掲示板(仮)</title>
</head>
<body>
<h1>掲示板(仮)</h1>
<?php
	//MySQL接続
$dsn = 'データベース名'; 
$user = 'ユーザー名'; 
$password = 'パスワード'; 
$pdo = new PDO($dsn,$user,$password);

//テーブル作成
$sql="create table notice"." (" . "id INT PRIMARY KEY AUTO_INCREMENT," . "name CHAR(32)," . "comment TEXT,"."input_at TIMESTAMP,"."passwords INT".");"; 
$stmt = $pdo->query($sql);

//編集内容を入力フォームに表示させるもの
$edit_pass=$_POST['edit_pass'];
$edit = $_POST['editNo'];
$sql = "SELECT * FROM notice WHERE id ='".$edit."' AND passwords ='".$edit_pass."'";
$results = $pdo -> query($sql); 
foreach ($results as $row){      
 $edit_id=$row['id'];    
 $edit_name=$row['name'];    
 $edit_comment=$row['comment']; 
 $edit_passwords=$row['passwords'];
}

?>
<form method="post" action="mission_4-1.php">
	<!--投稿-->
	<input type="text" name="name" placeholder="名前" value="<?php echo $edit_name;?>">
	<br><input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment;?>">
	<br><input type="text" name="pass" placeholder="パスワード" value="<?php echo $edit_passwords;?>">
    <!--編集判別-->
	<input type="hidden" name="edit_num" value="<?php echo $edit_id;?>">
	<input type="submit" value="送信">
	<!--削除-->
	<p><input type="text" name="deleteNo" placeholder="削除対象番号">
	<br><input type="text" name="delete_pass" placeholder="パスワード">
    <input type="submit" name="delete" value="削除"></p>
	<!--編集-->
     <p><input type="text" name="editNo" placeholder="編集対象番号">
     <br><input type="text" name="edit_pass" placeholder="パスワード">
    <input type="submit" name="edit" value="編集"></p>   
	</form>
<?php
//投稿
$name = $_POST['name'];
$comment = $_POST['comment'];
$pass = $_POST['pass'];
$edit_num = $_POST['edit_num'];
if(!empty($name) && (!empty($comment) && (!empty($pass) && (empty($edit_num))))){
	$sql = $pdo -> prepare("INSERT INTO notice (name, comment,passwords) VALUES (:name, :comment,:passwords)"); 
	$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
	$sql -> bindParam(':passwords', $pass, PDO::PARAM_INT); 
	 $sql -> execute();
}
//削除
$delete_pass=$_POST['delete_pass'];
$deleteNO = $_POST['deleteNo'];
$sql = $pdo -> prepare("DELETE FROM notice WHERE id = :deleteNO AND passwords = :delete_pass");
	$sql -> bindParam(':deleteNO', $deleteNO, PDO::PARAM_INT); 
	$sql -> bindParam(':delete_pass', $delete_pass, PDO::PARAM_INT); 
	$sql -> execute(); 

//編集
if(!empty($name) && (!empty($comment) && (!empty($pass) && (!empty($edit_num))))){
	$sql = $pdo -> prepare("UPDATE notice set name = :name,comment = :comment WHERE id = :edit_num"); 
	$sql -> bindParam(':name', $name, PDO::PARAM_STR); 
	$sql -> bindParam(':comment', $comment, PDO::PARAM_STR); 
	$sql -> bindParam(':edit_num', $edit_num, PDO::PARAM_INT);
	
	$sql -> execute();
}
//投稿表示
$sql = 'SELECT * FROM notice ORDER BY id ASC'; 
$results = $pdo -> query($sql); 
foreach ($results as $row){      
	echo $row['id'].'：';    
	echo $row['name'].'：';    
	echo $row['comment'].'：'; 
	echo $row['input_at']."<br>";
}

?>
</body>
</html>