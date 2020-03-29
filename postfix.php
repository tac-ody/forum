<?php
session_start();
setlocale(LC_CTYPE, 'C');

// 投稿一覧ページからrow_indexがPOSTされてきた場合、SESSION変数にそれを代入
// $_POST['row_index']は修正対象の$rowsのインデックス番号（forum.php 88行目でname命名）
if(isset($_POST['row_index'])) {
$row_index = $_POST['row_index'];
$_SESSION['row_index'] = $row_index;
}

// csvファイルを読み取りモードで開き、画面表示用に2次元配列をつくる
touch('CSV/BulletinBoard.csv');
$csv_open = fopen('CSV/BulletinBoard.csv', 'r');
while ($row = fgetcsv($csv_open)) {
  $row[3] = str_replace("<br>" ,"\r\n" , $row[3]);
  $rows[] = $row;
}
fclose($csv_open);

// 2次元配列の中でも、前ページで"修正"ボタンが押されたインデックス番号の配列だけ$fix_target_rowに代入
if(isset($_POST['row_index'])) {
$fix_target_row = $rows[$row_index];
}
 ?>
 <!DOCTYPE html>
 <html lang="jp" dir="ltr">
   <head>
     <meta charset="utf-8">
     <link rel="stylesheet" href="postfix.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <title>投稿修正</title>
   </head>
   <body>
       <div id="header">
         <h1>投稿修正</h1>
       </div>
        <div id="header_wrapper">
         <div id="name">名前
           <input type="text" name="name" class="id_box" value="<?php echo $_SESSION['name'] ?>" readonly>
         </div>
         <div id="email">Email
           <input type="text" name="email" class="id_box" value="<?php echo $_SESSION['email'] ?>" readonly>
         </div>
        </div>
     <form action="forum.php" method="POST">
       <input type="hidden" name="row_index" value="<?php echo $fix_target_row[4] ?>">
       <div class="contents_id">
           <div id="number">No.
               <input type="text" name="number" id="post_number_box" value="<?php echo $fix_target_row[0] ?>" readonly>
           </div>
           <div id="post_name">名前
               <input type="text" name="post_name" id="post_name_box" value="<?php echo $fix_target_row[1] ?>" readonly>
           </div>
           <div id="post_email">Email
               <input type="text" name="post_email" id="post_email_box" value="<?php echo $fix_target_row[2] ?>" readonly>
           </div>
       </div>
       <div class="contents">
           <textarea name="post" id="post_text" rows="15" readonly><?php echo $fix_target_row[3] ?></textarea>
       </div>
      <div id="footer">
          <input type="hidden" name="name" value="<?php echo $_SESSION['name'] ?>">
          <input type="hidden" name="email" value="<?php echo $_SESSION['email'] ?>">
        <textarea name="fix_post" id="text" rows="15" maxlength="500"><?php echo $fix_target_row[3] ?></textarea>
        <input type="submit" name="fix_post_button" id="new_post" value="送信">
      </div>
     </form>
     <script type="text/javascript" src="forum.js"></script>
   </body>
 </html>
