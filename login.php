<!DOCTYPE html>
<html lang="jp" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="login.css">
    <title>掲示板ログイン</title>
  </head>
  <body>
    <form action="forum.php" method="POST">
      <div id="header">
        <h1>掲示板 ログイン</h1>
      </div>
      <div id="name">名前:
        <input type="text" name="name" id="name_box" required>
      </div>
      <div id="email">Email:
        <input type="text" name="email" id="email_box" required>
      </div>
      <div id="footer">
        <button type="submit" id="submit">確認</button>
      </div>
    </form>
  </body>
</html>
