<?php
function openConn() {
  global $conn;
  $servername = "localhost";
  $username = "root";
  $password = "";
  
  try {
    $conn = new PDO("mysql:host=$servername;dbname=cms", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    return 0;
  }
}

// openConn();
// global $conn;
// $sql = "SELECT * FROM posts";
// $stmt = $conn->prepare($sql);
// $stmt->execute();
// $stmt->setFetchMode(PDO::FETCH_ASSOC);
// $rame = $stmt->fetchAll();
// while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
//   foreach ($result as $key => $value) {
//     # code...
//     echo $value;
//     echo $key;
    
//   }
//   var_dump($result);
//   echo "<br><br>";
// }
// foreach ($rame as $key => $value) {
//  echo $value["post_author"];
// }
// $sth = $conn->prepare("SELECT cat_title FROM categories");
// $sth->execute();

/* Fetch all of the remaining rows in the result set */
// print("Fetch all of the remaining rows in the result set:\n");
// $result = $sth->fetchAll();
// foreach ($result as $key => $value) {
  
//   print_r($value["cat_title"]);
// }
?>