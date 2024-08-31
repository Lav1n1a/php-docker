<?php
$HOST = getenv('HOST'); 
$USER = getenv('USERNAME');
$PASSWORD = getenv('PASSWORD');
$NAME = getenv('DATABASE_NAME');

$conn_str = "host=$HOST dbname=$NAME user=$USER password=$PASSWORD";

// Estabelecendo a conexão
$conn = pg_connect($conn_str);

if (!$conn) {
    echo "Falha na conexão: " . pg_last_error();
} 
pg_close($conn);

// $HOST = '3306'; 
// $USER = 'root';
// $PASSWORD = 'root'; 
// $NAME = 'agenda';

// $conn = new mysqli($HOST, $USER, $PASSWORD,$NAME);

// if ($conn->connect_error) {
//     die("Não foi possível conectar ao banco de dados: " . $conn->connect_error);
// } 
   
   
?>
