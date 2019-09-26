<?php

$url = $_SERVER['REQUEST_URI'];

// Checking if slash is first ccharacter in route otherwise add interface
if(strpos($url,"/") !== 0) {
  $url = "/$url";
}

$dbInstance = new DB();
$dbConn = $dbInstance->connect($db);

if($url == '/posts' && $_SERVER['REQUEST_METHOD'] == 'GET') {
  $posts = getAllPosts($dbConn);
  echo json_encode($posts);
}

if($url == '/posts' && $_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = $_POST;
  $postId = addPost($input, $dbConn);
  if($postId) {
    $input['id'] = $postId;
    $input['link'] = "/posts/$postId";
  }

  echo json_encode($input);
}

if (preg_match("/posts\/([0-9])+/", $url, $matches) && $_SERVER['REQUEST_METHOD'] == 'GET') {
  $postId = $matches[1];
  $post = getPost($dbConn, $postId);

  echo json_encode($post);
}

function getPost($db, $id) {
  $statement = $db->prepare("SELECT * FROM posts where id=:id");
  $statement->bindValue(':id', $id);
  $statement->execute();

  return $statement->fetch(PDO::FETCH_ASSOC);
}

function getAllPosts($db) {
  $statement = $db->prepare("SELECT * FROM posts");
  $statement->execute();
  $result = $statement->setFetchMode(PDO::FETCH_ASSOC);
  return $statement->fetchAll();
}

function addPost($input, $db) {
  $sql = "INSERT INTO posts
  (title, status, content, user_id)
  VALUES
  (:title, :status, :content, :user_id)";

  $statement = $db->prepare($sql);

  $statement->bindValue(':title', $input['title']);
  $statement->bindValue(':status', $input['status']);
  $statement->bindValue(':content', $input['content']);
  $statement->bindValue(':user_id', $input['user_id']);

  $statement->execute();

  return $db->lastInsertId();
}
