<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method === 'put') {

  parse_str(file_get_contents('php://input'), $input);

  $id = $input['id'] ?? null;
  $title = $input['title'] ?? null;
  $body = $input['body'] ?? null;

  $id = filter_var($id, FILTER_VALIDATE_INT);
  $title = filter_var($title, FILTER_SANITIZE_SPECIAL_CHARS);
  $body = filter_var($body, FILTER_SANITIZE_SPECIAL_CHARS);

  if ($id && $title && $body) {
    $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    $sql->fetch();

    if ($sql->rowCount() > 0) {
      $sql = $pdo->prepare("UPDATE notes SET title = :title, body = :body WHERE id = :id");
      $sql->bindValue(':title', $title);
      $sql->bindValue(':body', $body);
      $sql->bindValue(':id', $id);
      $sql->execute();

      $array['result'] = [
        'id' => $id,
        'title' => $title,
        'body' => $body
      ];  
    } else {
      $array['error'] = 'Not found ID';
    }
  } else {
    $array['error'] = 'Undefined ID, title or body';
  }
} else {
  $array['error'] = 'Unauthorized Method';
}

require('../return.php');
