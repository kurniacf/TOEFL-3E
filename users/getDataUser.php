<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $query = "SELECT * FROM users WHERE id_user = '$id_user'";
} else if (!empty($_GET['nrp_user'])) {
    $nrp_user = $_GET['nrp_user'];
    $query = "SELECT * FROM users WHERE nrp_user = '$nrp_user'";
} else if (!empty($_GET['nama_user'])) {
    $nama_user = $_GET['nama_user'];
    $query = "SELECT * FROM users WHERE nama_user = '$nama_user'";
} else {
    $query = "SELECT * FROM users ORDER BY id_user ASC";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }

    set_response(true, "User is Found", $data);
} else {
    set_response(false, "User is Not Found", $data);
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
