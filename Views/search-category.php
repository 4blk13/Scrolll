<?php

$connection=$db->getPDO();
if (!$connection){
    die ("Connection impossible");
}
else {
    if(isset($_GET['q'])) {
        if($stmt = $connection->prepare("SELECT id, name, thumbnail FROM category WHERE name LIKE CONCAT(?, '%');")){
            $stmt->bind_param("s", $_GET['q']);
            $stmt->execute();
            $stmt->bind_result($id, $name, $thumb);
            $searchResults = [];
            while ($stmt->fetch()) {
                $searchResults[] = ['name' => $name, 'id' => $id, 'thumb' => $thumb];
            }
            $stmt->close();
            echo json_encode($searchResults);
        }
    }
}
?>