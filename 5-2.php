<html>
<html lang= "ja">
<head>
   <meta charset = "utf-8">
</head>

<body>



<?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $dbpassword = 'パスワード';
    $pdo = new PDO($dsn, $user, $dbpassword, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    $sql = "CREATE TABLE IF NOT EXISTS TBL15"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "create_time timestamp not null default current_timestamp,"
    . "pass TEXT,"
    . "ed TEXT"
    .");";
    $stmt = $pdo-> query($sql);
    
        $editNum="";
        $editName="";
        $editComment="";
        $editPass="";             
        
        
	    // 送信内容によって処理が分かれる
	    if(!empty($_POST["editnum"])&&!empty($_POST["editpass"])&&isset($_POST["edit"])){
	            $editnum=$_POST["editnum"];
	            $editpass=$_POST["editpass"];
	            $sql = "SELECT*FROM TBL15 ";
                $stmt = $pdo->query($sql);
              
                $results = $stmt->fetchAll();
                foreach ($results as $row){
	            if($row['id']==$editnum&&$row['pass']==$editpass){ 
	           
                        $editNum=$row['id'];
                        $editName=$row['name'];
                        $editComment=$row['comment'];
                        $editPass=$row['pass'];
                    }
                }
	 
	    }else if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass"])&&isset($_POST["submit"])) {
		// 書き込みか上書きかをするところ
		    $name= htmlspecialchars($_POST["name"],ENT_QUOTES);
            $comment = htmlspecialchars($_POST["comment"],ENT_QUOTES);
            $pass=htmlspecialchars($_POST["pass"],ENT_QUOTES);
        
	
		    if(!empty($_POST["edit_n"])) {
		        
			    $sql = 'SELECT * FROM TBL15';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
			    foreach($results as &$row) {
				// 編集番号のところだったら上書き
				if($row['id'] == $_POST["edit_n"]) {
				    $id=$row['id'];
				    $name= htmlspecialchars($_POST["name"],ENT_QUOTES);
                    $comment = htmlspecialchars($_POST["comment"],ENT_QUOTES);
                    $pass=htmlspecialchars($_POST["pass"],ENT_QUOTES);
				     $ed="(編集済み)";
				    $stmt = $pdo->prepare('UPDATE TBL15 SET name=:name,comment=:comment,pass=:pass,ed=:ed WHERE id=:id');
				    $stmt -> bindParam(':id', $id, PDO::PARAM_STR);
                    $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt -> bindParam(':ed', $ed, PDO::PARAM_STR);
                    $stmt -> execute();
                    
				}
			}
		}
		else {
			// 新規投稿なので最後に追加
			$sql = $pdo -> prepare("INSERT INTO TBL15 (id, name, comment, pass) VALUES (:id, :name, :comment, :pass)");
		    $sql -> bindParam(':id', $id, PDO::PARAM_STR);
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
		}

	}
        

     
       if(!empty($_POST["deleteno"])&&!empty($_POST["delpass"])){
        
        $sql = 'SELECT * FROM TBL15';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
            if($row['id']==$_POST["deleteno"] && $row['pass']==$_POST["delpass"]){
                $id=$_POST["deleteno"];
                $sql = "delete from TBL15 where id=:id";
                $stmt = $pdo->prepare($sql);
                $stmt -> bindParam(':id', $id, PDO::PARAM_STR); 
                $stmt->execute();
                    }//if
	    }//foreach
       }//if
	 

?>

<H2>いつかやめたいと思っているけどやめられないことは？</H2>
 <form method= "post" action="">
 
   <input type="text" name="name" placeholder="名前" value="<?php if(!empty($editName)){echo $editName;}?>">
        <!--コメントの入力フォーム-->
    <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($editComment)){echo $editComment;}?>">
    <input type="hidden" name="edit_n" value="<?php if(!empty($editNum)){echo $editNum;}?>">
    <input type="text" name="pass" placeholder="パスワード" value="<?php if(!empty($editPass)){echo $editPass;}?>">
    <input type="submit" name="submit"><br>
        <!--消去の入力フォーム-->
    <input type="text" name="deleteno" value="" placeholder="削除対象番号">
    <input type="text" name="delpass" placeholder="パスワード">
    <input type="submit" name="deletebtn" value="削除"><br>

    <form method="POST" action="">
        <!--編集番号指定用フォーム-->
    <input type="text" name="editnum" placeholder="編集対象番号">
    <input type="text" name="editpass" placeholder="パスワード">
    <input type="submit" name="edit" value="編集">
  
  
  
  
</form>
<?php
   
    $sql = 'SELECT * FROM TBL15';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['create_time'].',';
        if(!empty($_POST["edit_n"])&&$row['id'] == $_POST["edit_n"]) {
            echo $row['ed'];
        }
    echo "<hr>";
    }

?>








</body>
</html>