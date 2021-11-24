<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_GET['id_user']) && !empty($_POST['id_session_user'])) {
    $id_user = $_GET['id_user'];
    $id_session_user = $_POST['id_session_user'];

    $query = "SELECT * FROM users WHERE id_user = '$id_user' AND id_session_user = '$id_session_user'";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $_SESSION = array(
            "login" => true,
            "data" => array(
                "id_session_user" => $id_session_user,
                "id_user" => $row["id_user"], // is not a must and not unsafe / you can let it out if you want
                "nrp_user" => $row["nrp_user"], // this is also not a must
                "department_user" => $row["department_user"],
                "hp_user" => $row["hp_user"],
                "time_session" => time() + 60 * 20
            )
        );
    }
    set_response(true, "Data is Found", $_SESSION);
} else {
    set_response(false, "Data is Not Found", $data);
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
