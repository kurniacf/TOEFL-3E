<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_POST['name_admin']) && !empty($_POST['nip_admin']) && !empty($_POST['password_admin'])) {
    $nip_admin = $_POST['nip_admin'];
    $name_admin = $_POST['name_admin'];
    $password_admin = password_hash($_POST['password_admin'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM admins WHERE nip_admin = '$nip_admin'";
    $get = pg_query($connect, $query);


    if (pg_num_rows($get)) {
        set_response(true, "NIP has Already Account!");
    } else {
        $query = "INSERT INTO admins(nip_admin, name_admin, password_admin) 
            VALUES ('$nip_admin', '$name_admin', '$password_admin')";

        $insert = pg_query($connect, $query);

        if ($insert) {
            set_response(true, "Register Admin success");
        } else {
            http_response_code(401);
            set_response(false, "Register Admin Failed");
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
