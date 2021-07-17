<?php
$connection=$db->getPDO();
if (!$connection){
    die ("Connection impossible");
}
else {
    if(isset($_GET['q'])) {
        if($stmt = $connection->prepare("SELECT name, id, thumbnail FROM category WHERE name LIKE CONCAT(?, '%');")){
            $stmt->bind_param("s", $_GET['q']);
            $stmt->execute();
            $stmt->bind_result($name, $id, $thumb);
            $searchResults = '';
            while ($stmt->fetch()) {
                $searchResults .= "<div class='search-results' onclick='chooseCategories(this, \"".$id."\", \"". $name ."\")'><img src='Assets/". $thumb ."' class='img-fluid search-thumbnail' style='padding:2%'></img><p class='text-center' style='margin:0%'>".$name."</p></div>";
            }
            $stmt->close();
            echo $searchResults;
        }
    }
}
?>