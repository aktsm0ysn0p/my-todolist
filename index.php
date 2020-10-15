<?php

require("./function.php");
createToken();
try {
  $db = parse_url($_SERVER['CLEARDB_DATABASE_URL']);
  $db['dbname'] = ltrim($db['path'], '/');
  $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset=utf8";
  $user = $db['user'];
  $password = $db['pass'];
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
  ];
  $dbh = new PDO($dsn, $user, $password, $options);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($_POST['btn'])) {
      validateToken();

      $text = mbTrim(filter_input(INPUT_POST, 'text'));
      $text = $text !== '' ? $text : '...';

      $in_pre = $dbh->prepare("INSERT INTO lists (list) VALUES (:text)");
      $in_pre->bindValue(':text', $text, PDO::PARAM_STR);
      $in_pre->execute();

      header('Location: https://mytodolist-app-1.herokuapp.com/result.php');
      exit;
    } else if (!empty($_POST['d_btn'])) {

      $d_id = trim(filter_input(INPUT_POST, 'd_id'));
      $in_pre = $dbh->prepare("DELETE FROM lists WHERE id = :id ");
      $in_pre->bindValue(':id', $d_id, PDO::PARAM_INT);
      $in_pre->execute();

      header('Location: https://mytodolist-app-1.herokuapp.com/result.php');
      exit;
    } else if (!empty($_POST['e_btn'])) {
      validateToken();
      $e_id = mbTrim(filter_input(INPUT_POST, 'e_id'));
      $e_text = mbTrim(filter_input(INPUT_POST, 'e_text'));

      $e_text = $e_text !== '' ? $e_text : '...';

      $up_pre = $dbh->prepare("UPDATE lists SET list = :newtext WHERE id = :id ");
      $up_pre->bindValue(':newtext', $e_text, PDO::PARAM_STR);
      $up_pre->bindValue(':id', $e_id, PDO::PARAM_INT);
      $editlist = $up_pre->execute();

      header('Location: https://mytodolist-app-1.herokuapp.com/result.php');
      exit;
    }
  }

  $prepare = $dbh->prepare("SELECT * FROM lists");
  $prepare->execute();

  $results = $prepare->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $error = $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Todo リスト</title>
  <link rel="stylesheet" href="main.css">
  <script src="https://kit.fontawesome.com/4d747ea1bc.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>My Todo List</header>
  <div class="main-wrapper">
    <div class="main">

      <div class="formset">
        <h2>Let's try!</h2>
        <form action="" method="post">
          <div class="textput">
            <input type="text" name="text" placeholder="What to add?" id="set_text" required>
          </div>
          <input type="submit" name="btn" value="追加" id="set_btn">
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
        </form>
      </div>

      <section>
        <ul>
          <?php if (!empty($results)) : ?>
            <?php foreach ($results as $list) : ?>
              <?php $id = $list['id']; ?>
              <li id="l_<?= h($id); ?>">
                <?= h($list['list']); ?>
                <i class="fas fa-pen fa-fw fa-pull-right"></i>
                <i class="fas fa-trash-alt fa-fw fa-pull-right"></i>
              </li>
              <section class="done d_block">
                <div class="modal-inner">
                  <p>このリストを本当に削除しますか？</p>
                  <p><?= h($list['list']); ?></p>
                  <form action="" method="post" class="d_form">
                    <div class="btn d__btn">キャンセル</div>
                    <input type="submit" name="d_btn" value="削除" class="btn">
                    <input type="hidden" name="d_id" value="<?= h($id); ?>">
                  </form>
                </div>
              </section>
              <section class="done e_block">
                <div class="modal-inner">
                  <p>リストを編集</p>
                  <form action="" method="post">
                    <input type="text" name="e_text" class="e_input" required>
                    <div class="e_form_btns">
                      <div class="btn e__btn">キャンセル</div>
                      <input type="submit" name="e_btn" value="編集する" class="btn">
                      <input type="hidden" name="e_id" value="<?= h($id); ?>">
                      <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
                    </div>
                  </form>
                </div>
              </section>
            <?php endforeach; ?>
          <?php endif; ?>
        </ul>
      </section>
    </div>
  </div>
  <script src="index.js"></script>
</body>

</html>
