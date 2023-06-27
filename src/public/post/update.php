<?php
$blogId = filter_input(INPUT_POST, 'blog_id');
$blogTitle = filter_input(INPUT_POST, 'blog_title');
$contents = filter_input(INPUT_POST, 'contents');

$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=blog; charset=utf8',
    $dbUserName,
    $dbPassword
);

$sql =
    'UPDATE blogs SET title = :blogTitle, contents = :contents WHERE id = :blogId';
try {
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':blogTitle', $blogTitle, PDO::PARAM_STR);
    $statement->bindValue(':contents', $contents, PDO::PARAM_STR);
    $statement->bindValue(':blogId', $blogId, PDO::PARAM_INT);
    $statement->execute();
    header('Location: ../myarticledetail.php?id=' . $blogId);
    exit();
} catch (PDOException $e) {
    session_start();
    $_SESSION['errors'][] = 'ブログ記事の編集に失敗しました。';
    header('Location: ../edit.php');
    exit();
}