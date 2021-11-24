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

if (!empty($_POST['nip_admin']) && !empty($_POST['password_admin'])) {
    $nip_admin = $_POST['nip_admin'];
    $password_admin = $_POST['password_admin'];

    $IdCheck = "SELECT id_admin FROM admins WHERE nip_admin = '$nip_admin'";
    $getId = pg_query($connect, $IdCheck);
    $rowId = pg_fetch_row($getId);

    if ($rowId) {
        $passwordDB = "SELECT password_admin FROM admins WHERE nip_admin = '$nip_admin'";
        $get = pg_query($connect, $passwordDB);
        $row = pg_fetch_row($get);
        $passwordORI = array_pop($row);

        $passwordCheck = password_verify($password_admin, $passwordORI);
        if ($passwordCheck) {
            $query1 = "SELECT id_admin FROM admins WHERE nip_admin = '$nip_admin'";
            $get1 = pg_query($connect, $query1);
            $data1 = pg_fetch_row($get1);
            $id_admin = intval(array_pop($data1));

            $query2 = "UPDATE admins set id_session_admin = '$id_session_admin' WHERE id_admin = '$id_admin'";
            $get2 = pg_query($connect, $query2);


            if (pg_num_rows($get1) > 0) {
                $_SESSION = array(
                    "login" => true,
                    "data" => array(
                        "id_session_admin" => $id_session_admin,
                        "id_admin" => $id_admin
                    )
                );
                set_response(true, "Login Admin success", $_SESSION);
            } else {
                http_response_code(401);
                set_response(false, "Login Admin failed", null);
            }
        } else {
            http_response_code(400);
            set_response(false, "Password False", "Please Check Your Password");
        }
    } else {
        http_response_code(400);
        set_response(false, "NIP False", "Please Check Your NIP");
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty", "Fill in NIP and Password");
}

function set_response($isSuccess, $message, $data)
{
    $resul = array(
        'isSuccess' => $isSuccess,
        'message' => $message,
        'data' => $data
    );

    echo json_encode($resul);
}
