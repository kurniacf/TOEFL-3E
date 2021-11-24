<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);

session_start();
$id_session_admin = session_id();
session_regenerate_id();
$id_session_admin = session_id();

if (!empty($_POST['name_admin']) && !empty($_POST['nip_admin']) && !empty($_POST['password_admin'])) {
    $nip_admin = $_POST['nip_admin'];
    $name_admin = $_POST['name_admin'];
    $password_admin = password_hash($_POST['password_admin'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM admins WHERE nip_admin = '$nip_admin'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        http_response_code(400);
        set_response(false, "NIP has Already Account!", "Check Data Again");
    } else {
        $query = "INSERT INTO admins(nip_admin, name_admin, password_admin, id_session_admin) 
            VALUES ('$nip_admin', '$name_admin','$password_admin', '$id_session_admin')";

        $insert = pg_query($connect, $query);

        if ($insert) {
            $query1 = "SELECT id_admin FROM admins WHERE nip_admin = '$nip_admin'";
            $get1 = pg_query($connect, $query1);
            $data1 = pg_fetch_row($get1);
            $id_admin = intval(array_pop($data1));

            $_SESSION = array(
                "register" => true,
                "data" => array(
                    "id_session_admin" => $id_session_admin,
                    "id_admin" => $id_admin
                )
            );
            set_response(true, "Register Admin success", $_SESSION);
        } else {
            http_response_code(401);
            set_response(false, "Register Admin Failed", null);
        }
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty!!", "Fill All Data");
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
