<?php
$HOST = getenv('HOST'); 
$USER = getenv('USERNAME');
$PASSWORD = getenv('PASSWORD');
$NAME = getenv('DATABASE_NAME');

$conn_str = "host=$HOST dbname=$NAME user=$USER password=$PASSWORD";

// Estabelecendo a conexão
$conn = pg_connect($conn_str);

if ($conn) {
    // echo "Conexão estabelecida com sucesso!";
} else {
    echo "Falha na conexão: " . pg_last_error();
}
   
?>
