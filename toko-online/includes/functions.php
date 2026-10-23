<?php
function e($v){return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function csrf_token(){if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function verify_csrf(){if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(400);exit('Permintaan tidak valid. Muat ulang halaman.');}}
function rupiah($n){return 'Rp '.number_format((float)$n,0,',','.');}
function cart_items(PDO $pdo){$items=[];$subtotal=0;foreach($_SESSION['cart']??[] as $id=>$qty){$s=$pdo->prepare('SELECT * FROM products WHERE id=? AND active=1');$s->execute([(int)$id]);if($p=$s->fetch()){ $p['quantity']=min((int)$qty,(int)$p['stock']);if($p['quantity']>0){$p['line_total']=$p['quantity']*(float)$p['price'];$subtotal+=$p['line_total'];$items[]=$p;}else unset($_SESSION['cart'][$id]);}else unset($_SESSION['cart'][$id]);}return [$items,$subtotal];}
function render_emoji($value){return htmlspecialchars($value,ENT_QUOTES,'UTF-8');}
?>
