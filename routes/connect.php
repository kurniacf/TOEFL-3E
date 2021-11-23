<?php
$host = "ec2-35-153-4-187.compute-1.amazonaws.com";
$port = "5432";
$user = "kliuvffwwpumbs";
$password = "fd7f7bac403687ac36d01993ba4ebb5c4866875d80c2ff6a439b75ad502cc0ec";
$dbname = "datfe99e46dgv4";

$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";
$connect = pg_connect($connection_string);

// if (!$connect) {
//     echo "Database connection failed.";
// } else {
//     echo "Database connection success.";
// }
