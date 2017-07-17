<?php

Class Database {

  /**
   * @var $conn PDO
   */
  private $conn;

  /**
   * Database constructor.
   * @param $login
   * @param $password
   * @param $base
   * @param string $host
   */
  public function __construct($login = null, $password = null, $base = null, $host = 'localhost')
  {
    if ($login) {
      $this->open($login, $password, $base, $host);
    }
  }

  /**
   * @param $login
   * @param $password
   * @param $base
   * @param $host
   * @throws Exception
   */
  public function open($login, $password, $base, $host = 'localhost')
  {
    $this->conn = new PDO("mysql:dbname=$base;host=$host", $login, $password);
    $this->conn->exec("set names utf8");
  }

  /**
   * @param $sql
   * @param $args
   * @return PDOStatement
   */
  public function execute($sql, $args = [])
  {
    $stmt = $this->conn->prepare($sql);
    $stmt->execute($args);

    return $stmt;
  }

  /**
   * @param null $name
   * @return string
   */
  public function lastInsertId($name = null)
  {
    return $this->conn->lastInsertId($name);
  }




}