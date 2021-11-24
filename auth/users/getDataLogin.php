<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

if (!empty($_POST['id_session_user'])) {
    $id_session_user = $_POST['id_session_user'];
    $query = "SELECT * FROM users WHERE id_session_user = '$id_session_user'";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $_SESSION = array(
            "login" => true,
            "data" => array(
                "id_session_user" => $id_session_user,
                "id_user" => $row["id_user"],
                "nrp_user" => $row["nrp_user"],
                "name_user" => $row["name_user"],
                "department_user" => $row["department_user"],
                "hp_user" => $row["hp_user"]
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
