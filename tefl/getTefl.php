<?php

include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    $query = "SELECT * FROM tefl WHERE id_user = '$id_user'";
} else if (!empty($_POST['nama_user'])) {
    $id_user = $_POST['id_user'];
    $query = "SELECT * FROM purchased WHERE id_user = '$id_user'";
} else {
    $query = "SELECT * FROM purchased ORDER BY id ASC";
}

$get = pg_query($connect, $query);

$data = array();
//$data2 = array();

if (pg_num_rows($get) > 0 || pg_num_rows($get2) > 0) {
    if ($get2 != NULL) {
        while ($row = pg_fetch_assoc($get2)) {
            $data[] = $row;
        }
    }
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    // if ($get2 !== 0) {
    //     while ($row = pg_fetch_assoc($get2)) {
    //         $data[] = $row;
    //     }
    // }

    set_response(true, "Data purchased ditemukan", $data);
} else {
    set_response(false, "Data purchased tidak ditemukan", $data);
}

function set_response($isSuccess, $message, $data)
{
    $result = array(
        'isSuccess' => $isSuccess,
        'message' => $message,
        'data' => $data
    );

    echo json_encode($result);
}
