<?php

function add_cards($resultat, $resultat_length, $column_number, $cursor) {
    $column_number .= '<div class="card">';
    if($resultat[$cursor][4] === 'image') {
        $column_number .= '<img src="Assets/Categorized/'. $resultat[$cursor][0].'_thumb' . '.' . $resultat[$cursor][5] .'" data-image-title="'. $resultat[$cursor][2] .'" class="img-fluid column-img" onclick="showModal(this);">';
    }
    elseif ($resultat[$cursor][4] === 'video') {
        $column_number .= '<video src="Assets/Categorized/'. $resultat[$cursor][0] . '.' . $resultat[$cursor][5] .'" data-image-title="'. $resultat[$cursor][2] .'" type="video/mp4" class="img-fluid" autoplay playsinline muted loop onclick="showModal(this, 1)"></video>';
    }
    else {
        throw new Exception("Wrong format file in db, file : " . $resultat[$cursor][0], 1);
    }
    $column_number .= '<div class="card-text text-center"><button type="button" class="btn btn-dark" onclick="chooseCategories(this, \''. $resultat[$cursor][3] .'\', \''. $resultat[$cursor][1] .'\')">'. $resultat[$cursor][1] .'</button>';
        while ((($cursor + 1) < ($resultat_length)) && ($resultat[$cursor + 1][0] === $resultat[$cursor][0])) {
            $cursor++;
            $column_number .= '<button type="button" class="btn btn-dark" onclick="chooseCategories(this, \''. $resultat[$cursor][3] .'\', \''. $resultat[$cursor][1] .'\')">'. $resultat[$cursor][1] .'</button>';
        }
    $column_number .= '</div></div>';
    return(array($column_number, $cursor));
}

$connection=$db->getPDO();
if (!$connection){
    die ("Connection impossible");
}
else {
    $where_clause = '';
    if(isset($_POST['json'])) {
        $json = json_decode($_POST['json']);
        if(!empty($json)) {
            $where_clause = "WHERE cat_id=".implode(" AND file_id IN (SELECT file_id FROM cat_files WHERE cat_id = ", $json);
            for ($i=0; $i < count($json) - 1; $i++) { 
                $where_clause .= ")";
            }
            $requestCat = mysqli_query($connection, "SELECT file_id from cat_files " . $where_clause);
            while ($i = mysqli_fetch_array($requestCat)) { 
                $array[] = $i[0];
            };
            $where_clause = "WHERE id IN ('". implode("','", $array) ."')";
        }
    }
    $cursor = $_GET['page'] * 9;
    $requete = mysqli_query($connection,"SELECT url, name, title, cat_id, format, extension FROM (SELECT * FROM files ". $where_clause ." ORDER BY id DESC LIMIT ". $cursor .", 9) f LEFT JOIN cat_files cf ON f.id = cf.file_id LEFT JOIN category c ON cf.cat_id = c.id;");
    $resultat = mysqli_fetch_all($requete);
    $resultat_length = count($resultat);
    $column_1 = '';
    $column_2 = '';
    $column_3 = '';
    $cursor = 0;
    $column_number = 1;
    while($cursor < $resultat_length) {
        switch ($column_number) {
            case 4:
                $column_number = 1;
            case 1:
                list($column_1, $cursor) = add_cards($resultat, $resultat_length, $column_1, $cursor);
                break;
            
            case 2:
                list($column_2, $cursor) = add_cards($resultat, $resultat_length, $column_2, $cursor);
                break;

            case 3:
                list($column_3, $cursor) = add_cards($resultat, $resultat_length, $column_3, $cursor);
                break;
        }
        $cursor++;
        $column_number++;
    }
    if ($column_1 === '' && $column_2 === '' && $column_3 === '') {
        die();
    }
    echo json_encode(array($column_1, $column_2, $column_3));
}

mysqli_close($connection);

?>