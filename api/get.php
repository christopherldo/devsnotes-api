<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method === 'get') {
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

  if ($id) {
    $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $data = $sql->fetch(PDO::FETCH_ASSOC);

      $array['result'] = [
        'id' => $data['id'],
        'title' => $data['title'],
        'body' => $data['body']
      ];
    } else {
      $array['error'] = 'Not found ID';
    }
  } else {
    $array['error'] = 'Undefined ID or ID not INT type';
  }
} else {
  $array['error'] = 'Unauthorized Method';
}

require('../return.php');
