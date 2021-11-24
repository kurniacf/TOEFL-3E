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

if (!empty($_POST['name_user']) && !empty($_POST['nrp_user']) && !empty($_POST['password_user'])) {

    $nrp_user = $_POST['nrp_user'];
    $name_user = $_POST['name_user'];
    $department_user = $_POST['department_user'];
    $hp_user = $_POST['hp_user'];
    $password_user = password_hash($_POST['password_user'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM users WHERE nrp_user = '$nrp_user'";
    $get = pg_query($connect, $query);


    if (pg_num_rows($get)) {
        set_response(true, "NRP has Already Account!", "Check Data Again");
    } else {
        $query = "INSERT INTO users(nrp_user, name_user, department_user, hp_user, password_user, id_session_user) 
            VALUES ('$nrp_user', '$name_user', '$department_user', '$hp_user','$password_user', '$id_session_user')";

        $insert = pg_query($connect, $query);

        if ($insert) {
            $query1 = "SELECT id_user FROM users WHERE nrp_user = '$nrp_user'";
            $get1 = pg_query($connect, $query1);
            $data1 = pg_fetch_row($get1);
            $id_user = intval(array_pop($data1));

            $_SESSION = array(
                "register" => true,
                "data" => array(
                    "id_session_user" => $id_session_user,
                    "id_user" => $id_user
                )
            );
            set_response(true, "Register User success", $_SESSION);
        } else {
            http_response_code(401);
            set_response(false, "Register User Failed", null);
        }
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty!!", "Fill in NRP, Name, and Password");
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
