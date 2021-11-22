<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_POST['nrp_user']) && !empty($_POST['password_user'])) {
    $nrp_user = $_POST['nrp_user'];
    $password_user = $_POST['password_user'];

    $emailCheck = "SELECT id_user FROM users WHERE nrp_user = '$nrp_user'";
    $getEmail = pg_query($connect, $emailCheck);
    $rowEmail = pg_fetch_row($getEmail);

    if ($rowEmail) {
        $passwordDB = "SELECT password_user FROM users WHERE nrp_user = '$nrp_user'";
        $get = pg_query($connect, $passwordDB);
        $row = pg_fetch_row($get);
        $passwordORI = array_pop($row);

        $passwordCheck = password_verify($password_user, $passwordORI);
        if ($passwordCheck) {
            $query = "SELECT * FROM users WHERE nrp_user = '$nrp_user'";
            $get = pg_query($connect, $query);
            $data = array();

            if (pg_num_rows($get) > 0) {
                while ($row = pg_fetch_assoc($get)) {
                    $data[] = $row;
                }
                set_response(true, "Login User success", $data);
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
