<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    $query = "SELECT * FROM users WHERE id_user = '$id_user'";
} else if (!empty($_POST['nrp_user'])) {
    $nrp_user = $_POST['nrp_user'];
    $query = "SELECT * FROM users WHERE nrp_user = '$nrp_user'";
} else if (!empty($_POST['name_user'])) {
    $name_user = urlencode($_POST['name_user']);
    $query = "SELECT * FROM users WHERE name_user = '$name_user'";
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
