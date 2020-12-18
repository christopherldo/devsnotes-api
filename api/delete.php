<?php

require('../config.php');

$method = strtolower($_SERVER['REQUEST_METHOD']);

if ($method === 'delete') {

  parse_str(file_get_contents('php://input'), $input);

  $id = $input['id'] ?? null;

  $id = filter_var($id, FILTER_VALIDATE_INT);

  if ($id) {
    $sql = $pdo->prepare("SELECT * FROM notes WHERE id = :id");
    $sql->bindValue(':id', $id);
    $sql->execute();

    $sql->fetch();

    if ($sql->rowCount() > 0) {
      $sql = $pdo->prepare("DELETE FROM notes WHERE id = :id");
      $sql->bindValue(':id', $id);
      $sql->execute();

      $array['result'] = "Deleted ID: $id";  
    } else {
      $array['error'] = 'Not found ID';
    }
  } else {
    $array['error'] = 'Undefined ID';
  }
} else {
  $array['error'] = 'Unauthorized Method';
}

require('../return.php');
