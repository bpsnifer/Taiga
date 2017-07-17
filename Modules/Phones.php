<?php

Class Phones extends ModuleAbstract
{
  public function execute()
  {
    switch ($this->getAction()) {
      case Actions::UPDATE:
        $this->update();
        break;
      case Actions::DELETE:
        $this->delete();
        break;
      case Actions::CREATE:
        $this->create();
        break;
    }

    $this->redirectToClient($_POST['client_id']);
  }

  private function update()
  {
    $sql = <<<__SQL
UPDATE phones set phone = :phone WHERE id=:id
__SQL;

    $stmt = $this->db->execute($sql, [
      'id' => $_GET['id'],
      'phone' => $_POST['phone']
    ]);
  }

  private function delete()
  {
    $sql = <<<__SQL
DELETE FROM phones WHERE id=:id
__SQL;

    $stmt = $this->db->execute($sql, [
      'id' => $_GET['id']
    ]);
  }


  private function create()
  {
    $sql = <<<__SQL
INSERT INTO phones (client_id, phone) VALUES (:client_id, :phone)
__SQL;

    $stmt = $this->db->execute($sql, [
      'phone' => $_POST['phone'],
      'client_id' => $_POST['client_id']
    ]);
  }

  private function redirectToClient($clientId)
  {
    header("Location: ?m=clients&a=" . Actions::READ . "&id={$clientId}");
    exit();
  }

}