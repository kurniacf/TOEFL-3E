<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_POST['name_user']) && !empty($_POST['nrp_user']) && !empty($_POST['password_user'])) {
    $nrp_user = $_POST['nrp_user'];
    $name_user = $_POST['name_user'];
    $department_user = $_POST['department_user'];
    $hp_user = $_POST['hp_user'];
    $password_user = password_hash($_POST['password_user'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM users WHERE nrp_user = '$nrp_user'";
    $get = pg_query($connect, $query);


    if (pg_num_rows($get)) {
        set_response(true, "NRP has Already Account!");
    } else {
        $query = "INSERT INTO users(nrp_user, name_user, department_user, hp_user, password_user) 
            VALUES ('$nrp_user', '$name_user', '$department_user', '$hp_user','$password_user')";

        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Register User success");
        } else {
            http_response_code(401);
            set_response(false, "Register User Failed");
        }
    }
} else {
    http_response_code(400);
    set_response(false, "Dont Empty!!");
}

function set_response($isSuccess, $message)
{
    $result = array(
        'isSuccess' => $isSuccess,
        'message' => $message
    );

    echo json_encode($result);
}
