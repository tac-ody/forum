<?php
session_start();
$_SESSION['name'] = $_POST['name'];
$_SESSION['email'] = $_POST['email'];
setlocale(LC_CTYPE, 'C');

//投稿No.をセッション変数に代入
if(empty($_SESSION['post_number']) && isset($_POST['post_button'])) {
    $_SESSION['post_number'] = 1;
} else if (!empty($_SESSION['post_number']) && isset($_POST['post_button'])) {
    $_SESSION['post_number']++;
}

//新規投稿の送信ボタンがPOSTされた場合にcsvファイルに新規投稿を追記
if(isset($_POST['post_button'])){
    $_POST['new_post'] = str_replace("\r\n" ,"<br>", $_POST['new_post']);
    $array_new_post = array($_SESSION['post_number'],$_SESSION['name'],$_SESSION['email'],$_POST['new_post'] );
    $csv_open = fopen('CSV/BulletinBoard.csv', 'a');
    fputcsv($csv_open, $array_new_post);
    fclose($csv_open);
}

touch('CSV/BulletinBoard.csv');
$csv_open = fopen('CSV/BulletinBoard.csv', 'r');
$rows = NULL;
$index_number = 0;
//csvファイルの読み込みと二次元配列生成（$rowsの中に$rowを代入していく）
//配列rowのインデックス番号4に添字を付与
while ($row = fgetcsv($csv_open)) {
  $row[3] = str_replace("<br>" ,"\r\n" , $row[3]);
  $row[4] = $index_number;
  $rows[] = $row;
  $index_number = $index_number+1;
}

//修正ボタンがPOSTされた場合にcsvファイルを上書き
// $_SESSION['row_index']は削除対象の$rowsのインデックス番号（postfix.phpで定義）
if(isset($_POST['fix_post_button'])){
  $index = $_SESSION['row_index'];
  $rows[$index][3] = $_POST['fix_post'];
  $csv_open = fopen('CSV/BulletinBoard.csv', 'w');
  foreach ($rows as $line) {
      $line = str_replace("\r\n" ,"<br>" , $line);
      array_pop($line);
      fputcsv($csv_open, $line);
   }
   fclose($csv_open);
}

//削除ボタンがPOSTされた場合にcsvファイルを上書き
// $_SESSION['row_index']は削除対象の$rowsのインデックス番号（postfix.phpで定義）
if(isset($_POST['post_delete'])) {
   $delete_index = $_POST['row_index'];
   unset($rows[$delete_index]);
   $csv_open = fopen('CSV/BulletinBoard.csv', 'w');
   foreach ((array)$rows as $line) {
       $line = str_replace("\r\n" ,"<br>" , $line);
       array_pop($line);
       fputcsv($csv_open, $line);
    }
    fclose($csv_open);
}
?>
<!DOCTYPE html>
<html lang="jp" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="forum.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <title>掲示板</title>
  </head>
  <body>
      <div id="header">
        <h1>掲示板</h1>
      </div>
       <div id="header_wrapper">
        <div id="name">名前
          <input type="text" name="name" class="id_box" value="<?php echo $_SESSION['name'] ?>" readonly>
        </div>
        <div id="email">Email
          <input type="text" name="email" class="id_box" value="<?php echo $_SESSION['email'] ?>" readonly>
        </div>
       </div>
  <?php foreach ((array)$rows as $row) { ?>
    <form action="postfix.php" method="POST">
      <input type="hidden" name="name" value="<?php echo $_SESSION['name'] ?>">
      <input type="hidden" name="email" value="<?php echo $_SESSION['email'] ?>">
      <input type="hidden" name="row_index" value="<?php echo $row[4] ?>">
      <div class="contents_id">
          <div id="number">No.
              <input type="text" name="number" id="post_number_box" value="<?php echo $row[0] ?>" readonly>
          </div>
          <div id="post_name">名前
              <input type="text" name="post_name" id="post_name_box" value="<?php echo $row[1] ?>" readonly>
          </div>
          <div id="post_email">Email
              <input type="text" name="post_email" id="post_email_box" value="<?php echo $row[2] ?>" readonly>
          </div>
      </div>
      <div class="contents">
          <textarea name="post" id="post_text" rows="15" readonly><?php echo $row[3] ?></textarea>
        <div class="contents_button">
          <input type="submit" name="post_fix" id="post_fix" value="修正">
          <input type="submit" name="post_delete" id="post_delete" formaction="forum.php" value="削除">
        </div>
      </div>
    </form>
   <?php } ?>
    <form action="forum.php" method="POST">
     <div id="footer">
         <input type="hidden" name="name" value="<?php echo $_SESSION['name'] ?>">
         <input type="hidden" name="email" value="<?php echo $_SESSION['email'] ?>">
       <textarea name="new_post" id="text" rows="15" maxlength="500" placeholder="※ここに新規投稿文を入力してください"></textarea>
       <input type="submit" name="post_button" id="new_post" value="送信">
     </div>
    </form>
    <script type="text/javascript" src="forum.js"></script>
  </body>
</html>
