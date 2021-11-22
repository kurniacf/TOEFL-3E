<?php
include_once('../routes/connect.php');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization, X-Requested-With');

if (!empty($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
    $listening_tefl = $_POST['listening_tefl'];
    $grammar_tefl = $_POST['grammar_tefl'];
    $reading_tefl = $_POST['reading_tefl'];

    $query = "SELECT * FROM tefl WHERE id_user = '$id_user'";
    $get = pg_query($connect, $query);

    if (pg_num_rows($get)) {
        $query = "UPDATE tefl set listening_tefl = '$listening_tefl', grammar_tefl = '$grammar_tefl', reading_tefl = '$reading_tefl' 
            WHERE id_user = '$id_user'";
        $insert = pg_query($connect, $query);

        $query1 = "SELECT listening_tefl FROM tefl WHERE id_user = '$id_user'";
        $query2 = "SELECT grammar_tefl FROM tefl WHERE id_user = '$id_user'";
        $query3 = "SELECT reading_tefl FROM tefl WHERE id_user = '$id_user'";

        $get1 = pg_query($connect, $query1);
        $data1 = pg_fetch_row($get1);

        $get2 = pg_query($connect, $query2);
        $data2 = pg_fetch_row($get2);

        $get3 = pg_query($connect, $query3);
        $data3 = pg_fetch_row($get3);

        $listening_tefl = intval(array_pop($data1));
        $grammar_tefl = intval(array_pop($data2));
        $reading_tefl = intval(array_pop($data3));

        $avg_tefl = intval(($listening_tefl + $grammar_tefl + $reading_tefl) / 3);

        $query4 = "UPDATE tefl set avg_tefl = '$avg_tefl' WHERE id_user = '$id_user'";
        $insert1 = pg_query($connect, $query4);

        if ($insert1) {
            set_response(true, "Update TOEFL Success");
        } else {
            http_response_code(401);
            set_response(false, "Update TOEFL Failed");
        }
    } else {
        $query = "INSERT INTO tefl(id_user, listening_tefl, grammar_tefl, reading_tefl) 
            VALUES ('$id_user', '$listening_tefl', '$grammar_tefl', '$reading_tefl')";

        $insert = pg_query($connect, $query);

        $query1 = "SELECT listening_tefl FROM tefl WHERE id_user = '$id_user'";
        $query2 = "SELECT grammar_tefl FROM tefl WHERE id_user = '$id_user'";
        $query3 = "SELECT reading_tefl FROM tefl WHERE id_user = '$id_user'";

        $get1 = pg_query($connect, $query1);
        $data1 = pg_fetch_row($get1);

        $get2 = pg_query($connect, $query2);
        $data2 = pg_fetch_row($get2);

        $get3 = pg_query($connect, $query3);
        $data3 = pg_fetch_row($get3);

        $listening_tefl = intval(array_pop($data1));
        $grammar_tefl = intval(array_pop($data2));
        $reading_tefl = intval(array_pop($data3));

        $avg_tefl = intval(($listening_tefl + $grammar_tefl + $reading_tefl) / 3);

        $query4 = "UPDATE tefl set avg_tefl = '$avg_tefl' WHERE id_user = '$id_user'";
        $insert1 = pg_query($connect, $query4);

        if ($insert1) {
            set_response(true, "Input TOEFL Success");
        } else {
            http_response_code(401);
            set_response(false, "Input TOEFL Failed");
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
