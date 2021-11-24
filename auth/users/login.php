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
$id_session_user = session_id();
session_regenerate_id();
$id_session_user = session_id();

if (!empty($_POST['nrp_user']) && !empty($_POST['password_user'])) {
    $nrp_user = $_POST['nrp_user'];
    $password_user = $_POST['password_user'];

    $IdCheck = "SELECT id_user FROM users WHERE nrp_user = '$nrp_user'";
    $getId = pg_query($connect, $IdCheck);
    $rowId = pg_fetch_row($getId);

    if ($rowId) {
        $passwordDB = "SELECT password_user FROM users WHERE nrp_user = '$nrp_user'";
        $get = pg_query($connect, $passwordDB);
        $row = pg_fetch_row($get);
        $passwordORI = array_pop($row);

        $passwordCheck = password_verify($password_user, $passwordORI);
        if ($passwordCheck) {
            $query1 = "SELECT id_user FROM users WHERE nrp_user = '$nrp_user'";
            $get1 = pg_query($connect, $query1);
            $data1 = pg_fetch_row($get1);
            $id_user = intval(array_pop($data1));

            $query2 = "UPDATE users set id_session_user = '$id_session_user' WHERE id_user = '$id_user'";
            $get2 = pg_query($connect, $query2);


            if (pg_num_rows($get1) > 0) {
                $_SESSION = array(
                    "login" => true,
                    "data" => array(
                        "id_session_user" => $id_session_user,
                        "id_user" => $id_user
                    )
                );
                set_response(true, "Login User success", $_SESSION);
            } else {
                set_response(false, "Login User failed", $data);
            }
        } else {
            http_response_code(400);
            set_response(false, "Password False", "Please Check Your Password");
        }
    } else {
        http_response_code(404);
        set_response(false, "NRP False", "Please Check Your NRP");
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty", "Fill in NRP and Password");
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
