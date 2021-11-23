<?php
include_once('../../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_POST['nip_admin']) && !empty($_POST['password_admin'])) {
    $nip_admin = $_POST['nip_admin'];
    $password_admin = $_POST['password_admin'];

    $emailCheck = "SELECT id_admin FROM admins WHERE nip_admin = '$nip_admin'";
    $getEmail = pg_query($connect, $emailCheck);
    $rowEmail = pg_fetch_row($getEmail);

    if ($rowEmail) {
        $passwordDB = "SELECT password_admin FROM admins WHERE nip_admin = '$nip_admin'";
        $get = pg_query($connect, $passwordDB);
        $row = pg_fetch_row($get);
        $passwordORI = array_pop($row);

        $passwordCheck = password_verify($password_admin, $passwordORI);
        if ($passwordCheck) {
            $query = "SELECT * FROM admins WHERE nip_admin = '$nip_admin'";
            $get = pg_query($connect, $query);
            $data = array();

            if (pg_num_rows($get) > 0) {
                while ($row = pg_fetch_assoc($get)) {
                    $_SESSION = array(
                        "login" => true,
                        "data" => array(
                            "id_admin" => $row["id_admin"], // is not a must and not unsafe / you can let it out if you want
                            "nip_admin" => $row["nip_admin"], // this is also not a must
                            "name_admin" => $row["name_admin"],
                            "time" => time() + 60 * 20 // so here you can set a time how long the session is available. for this example it is 10min.
                        )
                    );
                    //$data[] = $row;
                }
                set_response(true, "Login Admin success", $_SESSION);
            } else {
                set_response(false, "Login Admin failed", $data);
            }
        } else {
            http_response_code(400);
            set_response(false, "Password False", "Please Check Your Password");
        }
    } else {
        http_response_code(404);
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
