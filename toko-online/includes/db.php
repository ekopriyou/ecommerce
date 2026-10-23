<?php
$host=getenv('TOKOKITA_DB_HOST')?:'127.0.0.1';$dbname=getenv('TOKOKITA_DB_NAME')?:'tokokita_shop';$user=getenv('TOKOKITA_DB_USER')?:'root';$pass=getenv('TOKOKITA_DB_PASS')?:'';
try{$pdo=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4",$user,$pass,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(PDOException $ex){error_log('TokoKita database connection failed: '.$ex->getMessage());http_response_code(500);exit('Layanan data sedang tidak tersedia. Periksa konfigurasi server.');}
?>
