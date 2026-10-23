<?php
require_once __DIR__.'/../includes/security.php';
require __DIR__.'/../includes/db.php';
require_admin();
require __DIR__.'/_layout.php';
$id=(int)($_GET['id']??0);$s=$pdo->prepare('SELECT * FROM orders WHERE id=?');$s->execute([$id]);$o=$s->fetch();
if(!$o){http_response_code(404);exit('Pesanan tidak ditemukan.');}
$allowed=['Menunggu pembayaran','Diproses','Dikirim','Selesai','Dibatalkan'];$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();$new=$_POST['status']??'';
 if(!in_array($new,$allowed,true)){$error='Status tidak valid.';}else{
  try{$pdo->beginTransaction();$lock=$pdo->prepare('SELECT status FROM orders WHERE id=? FOR UPDATE');$lock->execute([$id]);$old=$lock->fetchColumn();
   if(!$old)throw new RuntimeException('Pesanan tidak ditemukan.');
   if($old!==$new){
    if($old!=='Dibatalkan'&&$new==='Dibatalkan'){$q=$pdo->prepare('SELECT product_id,quantity FROM order_items WHERE order_id=?');$q->execute([$id]);foreach($q->fetchAll() as $line)$pdo->prepare('UPDATE products SET stock=stock+? WHERE id=?')->execute([$line['quantity'],$line['product_id']]);}
    elseif($old==='Dibatalkan'&&$new!=='Dibatalkan'){$q=$pdo->prepare('SELECT product_id,quantity FROM order_items WHERE order_id=?');$q->execute([$id]);foreach($q->fetchAll() as $line){$u=$pdo->prepare('UPDATE products SET stock=stock-? WHERE id=? AND stock>=?');$u->execute([$line['quantity'],$line['product_id'],$line['quantity']]);if(!$u->rowCount())throw new RuntimeException('Stok produk tidak cukup untuk mengaktifkan kembali pesanan.');}}
    $pdo->prepare('UPDATE orders SET status=? WHERE id=?')->execute([$new,$id]);admin_event($pdo,'order_status_changed',$id,$old.' → '.$new);
   }
   $pdo->commit();header('Location: order.php?id='.$id.'&updated=1');exit;
  }catch(Throwable $ex){if($pdo->inTransaction())$pdo->rollBack();$error=$ex instanceof RuntimeException?$ex->getMessage():'Status pesanan tidak dapat diperbarui.';}
 }
}
$items=$pdo->prepare('SELECT product_name,price,quantity,price*quantity line_total FROM order_items WHERE order_id=? ORDER BY id');$items->execute([$id]);$items=$items->fetchAll();
admin_header('Detail pesanan');
?>
<main class="adminmain"><div class="adminhead"><div><span class="eyebrow">DETAIL TRANSAKSI</span><h1><?=e($o['order_number'])?></h1></div><div style="display:flex;gap:8px;flex-wrap:wrap"><a class="smallbtn" href="print-order.php?id=<?=$id?>&amp;type=invoice" target="_blank" rel="noopener">Cetak invoice</a><a class="smallbtn" href="print-order.php?id=<?=$id?>&amp;type=packing" target="_blank" rel="noopener">Cetak lembar packing</a><a href="orders.php">← Kembali ke pesanan</a></div></div><?php if($error):?><div class="notice error"><?=e($error)?></div><?php elseif(isset($_GET['updated'])):?><div class="notice">Status pesanan telah diperbarui.</div><?php endif?><div class="detailgrid"><section class="panel"><h2>Informasi pelanggan</h2><div class="data-list"><strong>Nama</strong><span><?=e($o['customer_name'])?></span><strong>Email</strong><span><?=e($o['email'])?></span><strong>Telepon</strong><span><?=e($o['phone'])?></span><strong>Alamat</strong><span><?=nl2br(e($o['address']))?><br><?=e($o['city'])?>, <?=e($o['postal_code'])?></span><strong>Dibuat</strong><span><?=e($o['created_at'])?></span></div></section><section class="panel summary"><h2>Status &amp; pembayaran</h2><p>Status saat ini: <span class="status-pill"><?=e($o['status'])?></span></p><form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><div class="field"><label>Ubah status pesanan</label><select name="status"><?php foreach($allowed as $s):?><option <?=$o['status']===$s?'selected':''?> value="<?=e($s)?>"><?=e($s)?></option><?php endforeach?></select></div><button class="smallbtn" style="margin-top:12px">Simpan status</button></form><p class="adminnote">Pembayaran belum terhubung ke gateway. Pastikan pembayaran manual sebelum menandai Diproses.</p></section></div><section class="panel" style="margin-top:16px"><h2>Item pesanan</h2><div class="tablewrap"><table class="admintable"><thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Total</th></tr></thead><tbody><?php foreach($items as $it):?><tr><td><?=e($it['product_name'])?></td><td><?=rupiah($it['price'])?></td><td><?=number_format($it['quantity'])?></td><td><?=rupiah($it['line_total'])?></td></tr><?php endforeach?></tbody></table></div><div class="sumline"><span>Subtotal</span><span><?=rupiah($o['subtotal'])?></span></div><div class="sumline"><span>Pengiriman</span><span><?=rupiah($o['shipping'])?></span></div><div class="sumline total"><span>Total</span><span><?=rupiah($o['total'])?></span></div></section></main><?php admin_footer();?>