<?php
require_once "database_class.php";

class DatabaseMysqli extends Database {
  private $mysqli = NONE;

  public function __construct($host, $database, $user, $password) {
    $this->mysqli = new mysqli($host, $user, $password, $database);

    if ($mysqli->connect_errno) {
      // The connection failed. What do you want to do?
      // You could contact yourthis (email?), log the error, show a nice page, etc.
      // You do not want to reveal sensitive information

      // Let's try this:
      echo "Sorry, this website is experiencing problems.";

      // Something you should not do on a public site, but this example will show you
      // anyways, is print out MySQL error related information -- you might log this
      echo "Error: Failed to make a MySQL connection, here is why: \n";
      echo "Errno: " . $mysqli->connect_errno . "\n";
      echo "Error: " . $mysqli->connect_error . "\n";

      // You might want to show them something nice, but we will simply exit
      exit;
    }
  }

  public function execute($sql) {
    $result = $this->mysqli->query($sql);
    return $result->fetch_all();
  }
}
