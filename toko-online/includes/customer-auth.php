<?php
require_once __DIR__.'/security.php';
function current_customer(){return $_SESSION['customer']??null;}
function require_customer(){if(!current_customer()){$_SESSION['customer_return_to']='/toko-online/account.php';header('Location: /toko-online/customer/login.php');exit;}}
function customer_login_token(){if(empty($_SESSION['customer_login_csrf']))$_SESSION['customer_login_csrf']=bin2hex(random_bytes(32));return $_SESSION['customer_login_csrf'];}
function verify_customer_login_token(){if(!hash_equals($_SESSION['customer_login_csrf']??'',$_POST['login_csrf']??'')){http_response_code(400);exit('Permintaan tidak valid. Muat ulang halaman.');}}
function customer_nav(){ $c=current_customer();?><?php if($c):?><a href="/toko-online/account.php">Akun saya</a><form action="/toko-online/customer/logout.php" method="post" style="display:inline"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><button style="border:0;background:none;color:inherit;cursor:pointer">Keluar (<?=e($c['name'])?>)</button></form><?php else:?><a href="/toko-online/customer/login.php">Masuk</a><a href="/toko-online/customer/register.php">Daftar</a><?php endif?><?php }
?>