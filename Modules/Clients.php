<?php

Class Clients extends ModuleAbstract
{
  public function execute()
  {

    $response = null;

    switch ($this->getAction()) {
      case Actions::LIST:
        $response = $this->list();
        break;
      case Actions::READ:
        $response = $this->read();
        break;
      case Actions::UPDATE:
        $response = $this->update();
        break;
      case Actions::CREATE:
        $response = $this->create();
        break;
      case Actions::DELETE:
        $response = $this->delete();
        break;
    }

    return $response;
  }

  private function getFilters()
  {
    return [
      'phones',
      'second_name'
    ];
  }

  private function update()
  {
    $sql = <<<__SQL
UPDATE clients SET second_name=:second_name, name=:name, middle_name=:middle_name, birthday=:birthday, gender=:gender, date_update=NOW() WHERE id=:id
__SQL;

    $stmt = $this->db->execute($sql, [
      'id' => $_GET['id'],
      'second_name' => $_POST['second_name'],
      'name' => $_POST['name'],
      'middle_name' => $_POST['middle_name'],
      'birthday' => $_POST['birthday'],
      'gender' => $_POST['gender'] ? 1 : 0
    ]);

    return $this->read();
  }

  private function create()
  {
    $sql = <<<__SQL
INSERT INTO clients (second_name,name,middle_name,birthday,gender) 
VALUES (:second_name, :name, :middle_name, :birthday, :gender)
__SQL;

    $stmt = $this->db->execute($sql, [
      'second_name' => $_POST['second_name'],
      'name' => $_POST['name'],
      'middle_name' => $_POST['middle_name'],
      'birthday' => $_POST['birthday'],
      'gender' => $_POST['gender'] ? 1 : 0
    ]);

    $this->redirect("?m=clients&a=" . Actions::READ . "&id={$this->db->lastInsertId()}");
  }

  private function read($id = null)
  {
    if (!$id) {
      $id = $_GET['id'];
    }

    $sql = <<<__SQL
SELECT * FROM clients WHERE id=:id
__SQL;

    $stmt = $this->db->execute($sql, [
      'id' => $id
    ]);
    $client = $stmt->fetch();

    $template = new Template('client');
    $template->render(
      [
        'client' => $client,
        'phones' => isset($client['id']) ? $this->getPhones($client['id']) : []
      ]
    );
  }


  private function list()
  {
    $where = [];
    $params = [];

    foreach ($this->getFilters() as $filter) {
      if (!empty($_GET[$filter])) {
        $where[] = "{$filter} LIKE :{$filter}";
        $params[$filter] = "%{$_GET[$filter]}%";
      }
    }

    $sql = <<<__SQL
SELECT * FROM (SELECT c.*, (SELECT GROUP_CONCAT(p.phone SEPARATOR ', ') FROM phones AS p WHERE p.client_id = c.id) AS phones FROM clients AS c) AS x
__SQL;

    if (!empty($where)) {
      $sql = sprintf("%s WHERE %s",
        $sql,
        join('OR ', $where)
      );
    }

    $stmt = $this->db->execute($sql, $params);
    $clients = $stmt->fetchAll();

    $template = new Template('clients');
    $template->render(
      [
        'clients' => $clients
      ]
    );
  }

  private function delete()
  {
    $sql = <<<__SQL
DELETE FROM clients WHERE id=:id
__SQL;

    $stmt = $this->db->execute($sql, [
      'id' => $_GET['id']
    ]);

    $this->redirect("?m=clients&a=" . Actions::LIST);
  }

  private function getPhones($clientId)
  {
    $sql = <<<__SQL
SELECT * FROM phones WHERE client_id = :client_id
__SQL;

    $stmt = $this->db->execute($sql, ['client_id' => $clientId]);
    $phones = $stmt->fetchAll();

    return $phones;
  }

}