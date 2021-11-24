<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_session_admin'])) {
    $id_session_admin = $_POST['id_session_admin'];
    $query = "SELECT * FROM admins WHERE id_session_admin = '$id_session_admin'";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $_SESSION = array(
            "login" => true,
            "data" => array(
                "id_session_admin" => $id_session_admin,
                "id_admin" => $row["id_admin"],
                "nip_admin" => $row["nip_admin"],
                "name_admin" => $row["name_admin"]
            )
        );
    }
    set_response(true, "Data is Found", $_SESSION);
} else {
    http_response_code(400);
    set_response(false, "Data is Not Found", "Session is Wrong!");
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
