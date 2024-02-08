<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <h2><span style="color: #528386">みんなの好きな食べ物を教えて！</span></h2>
    <?php
        $dsn = 'mysql:dbname=データベース名;host=localhost';
        $user = 'ユーザ名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
        $sql = "CREATE TABLE IF NOT EXISTS tb5_1"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name CHAR(32),"
        . "comment TEXT,"
        . "date TEXT,"
        . "password CHAR(32)"
        .");";
        $stmt = $pdo->query($sql);
    
        //新規投稿
        if(!empty($_POST["submit1"]) && !empty($_POST["password"])){
            $name = $_POST["name"];
            $cmnt = $_POST["comment"];
            $pass = $_POST["password"];
            $date = date("Y/m/d H:i:s");
            
            //newINSERT
            if(empty($_POST["number_edited"])){
                $sql = "INSERT INTO tb5_1 (name, comment, date, password) VALUES (:name, :comment, :date, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $cmnt, PDO::PARAM_STR);
                $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                $stmt->bindParam(':password', $pass, PDO::PARAM_STR);
                $stmt->execute();
            }
            //UPDATE
            else{
                $id = $_POST["number_edited"]; //変更する投稿番号
                $sql = 'UPDATE tb5_1 SET name=:name,comment=:comment WHERE id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':comment', $cmnt, PDO::PARAM_STR);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
        }
        
        //削除
        elseif(!empty($_POST["submit2"]) && !empty($_POST["delete_password"])){
            $d_num = $_POST["delete_number"];
            $d_pass = $_POST["delete_password"];
            $d_bool = false;

            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $d_num && $row['password'] == $d_pass){
                    $d_bool = true;
                    break;
                }
            }

            if($d_bool){
                $id = $d_num;
                $sql = 'delete from tb5_1 where id=:id';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            else echo "error：正しいパスワードを入力してください<br>";
        }
        
        //編集
        elseif(!empty($_POST["submit3"]) && !empty($_POST["edit_password"])){
            $e_num = $_POST["edit_number"];
            $e_pass = $_POST["edit_password"];
            $e_bool = false;
            $e_name = "";
            $e_cmnt = "";
            
            $sql = 'SELECT * FROM tb5_1';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                if($row['id'] == $e_num && $row['password'] == $e_pass){
                    $e_bool = true;
                    $e_name = $row['name'];
                    $e_cmnt = $row['comment'];
                    break;
                }
            }
            
            if(!($e_bool)) echo "error：正しいパスワードを入力してください<br>";
        }
    ?>
    <hr>
    
    <form action="" method="post">
        【投稿フォーム】<br>
        お名前：<input type='text' name='name' value="<?php if(!empty($e_name)) echo $e_name; ?>"><br>
        コメント：<input type='text' name='comment' value="<?php if(!empty($e_cmnt)) echo $e_cmnt; ?>"><br>
        パスワード：<input type='password' name='password'><br>
        <input type='submit' name='submit1' value='送信'><br>

        【削除フォーム】<br>
        削除対象番号：<input type='number' min='1' name='delete_number' placeholder="削除したい投稿の番号"><br>
        パスワード：<input type='password' name='delete_password'><br>
        <input type='submit' name='submit2' value='削除'><br>

        【編集フォーム】<br>
        編集対象番号：<input type='number' min='1' name='edit_number' placeholder="編集したい投稿の番号"><br>
        パスワード：<input type='password' name='edit_password'><br>
        <input type='submit' name='submit3' value='編集'><br>
        <input type='hidden' name='number_edited' value="<?php if(!empty($e_num)) echo $e_num; ?>"><br>
    </form>
    
    
    <?php
        //表示
        $sql = 'SELECT * FROM tb5_1';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date'].'<br>';
        }
    ?>
    
    
</body>
</html>