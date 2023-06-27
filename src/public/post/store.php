<?php
session_start();
$userId = $_SESSION['id'];
$blogTitle = filter_input(INPUT_POST, 'blog_title');
$contents = filter_input(INPUT_POST, 'contents');

if (empty($blogTitle) || empty($contents)) {
    $_SESSION['errors'] = 'タイトルか内容の入力がありません';
    header('Location: ../create.php');
    exit();
}

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=blog; charset=utf8',
    $dbUserName,
    $dbPassword
);

$sql =
    'INSERT INTO blogs(user_id, title, contents) VALUES(:userId, :blogTitle, :contents)';
try {
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':userId', $userId, PDO::PARAM_INT);
    $statement->bindValue(':blogTitle', $blogTitle, PDO::PARAM_STR);
    $statement->bindValue(':contents', $contents, PDO::PARAM_STR);
    $statement->execute();
    header('Location: ../mypage.php');
    exit();
} catch (PDOException $e) {
    $_SESSION['errors'][] = 'ブログ記事の登録に失敗しました。';
    header('Location: ../create.php');
    exit();
}