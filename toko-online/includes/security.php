<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $https = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_name('tokokita_session');
    session_set_cookie_params(['lifetime'=>0,'path'=>'/toko-online/','secure'=>$https,'httponly'=>true,'samesite'=>'Lax']);
    ini_set('session.use_strict_mode','1');
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: DENY');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
require_once __DIR__.'/functions.php';
if (empty($_SESSION['_created'])) { session_regenerate_id(true); $_SESSION['_created']=time(); }
if (!empty($_SESSION['_last_activity']) && time()-(int)$_SESSION['_last_activity']>7200) { $_SESSION=[]; session_regenerate_id(true); }
$_SESSION['_last_activity']=time();
function admin_user(){return $_SESSION['admin']??null;}
function require_admin(){if(!admin_user()){header('Location: /toko-online/admin/login.php');exit;}}
function admin_csrf(){return csrf_token();}
function admin_event(PDO $pdo,string $action,?int $orderId=null,string $details=''){ $u=admin_user();$s=$pdo->prepare('INSERT INTO admin_audit(admin_id,admin_username,action,order_id,details,ip_address) VALUES(?,?,?,?,?,?)');$s->execute([$u['id']??null,$u['username']??'system',$action,$orderId,mb_substr($details,0,255),$_SERVER['REMOTE_ADDR']??'']); }
function admin_login_csrf(){if(empty($_SESSION['admin_login_csrf']))$_SESSION['admin_login_csrf']=bin2hex(random_bytes(32));return $_SESSION['admin_login_csrf'];}
function verify_admin_login_csrf(){if(!hash_equals($_SESSION['admin_login_csrf']??'',$_POST['login_csrf']??'')){http_response_code(400);exit('Permintaan tidak valid. Muat ulang halaman.');}}
function admin_login_form_token(){return admin_login_csrf();}
function admin_login_check(){verify_admin_login_csrf();}
?>