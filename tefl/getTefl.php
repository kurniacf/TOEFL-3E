<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');
$rest_json = file_get_contents("php://input");
$_POST = json_decode($rest_json, true);


if (!empty($_POST['id_user'])) {
    $id_user = $_POST['id_user'];
    $query = "SELECT users.nrp_user, users.name_user, users.department_user, users.hp_user, 
        tefl.listening_tefl, tefl.grammar_tefl, tefl.reading_tefl, tefl.avg_tefl
        FROM users,tefl WHERE users.id_user = '$id_user' AND tefl.id_user = '$id_user'";
} else {
    $query = "SELECT users.nrp_user, users.name_user, users.department_user, users.hp_user, 
        tefl.listening_tefl, tefl.grammar_tefl, tefl.reading_tefl, tefl.avg_tefl
        FROM users, tefl ORDER BY users.id_user ASC";
}

$get = pg_query($connect, $query);

$data = array();

if (pg_num_rows($get) > 0) {
    while ($row = pg_fetch_assoc($get)) {
        $data[] = $row;
    }
    set_response(true, "Data is Found", $data);
} else {
    http_response_code(401);
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
