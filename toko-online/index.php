<?php
// TokoKita - MVP toko online PHP + MySQL untuk XAMPP
require_once __DIR__ . '/includes/security.php';
require __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/customer-auth.php';
$cats = $pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
$cat = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;
$q = trim($_GET['q'] ?? '');
$sql='SELECT p.*,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id WHERE p.active=1'; $args=[];
if ($cat) { $sql.=' AND p.category_id=?'; $args[]=$cat; }
if ($q!=='') { $sql.=' AND (p.name LIKE ? OR p.description LIKE ?)'; $args[]="%$q%"; $args[]="%$q%"; }
$sql.=' ORDER BY p.featured DESC,p.id DESC'; $st=$pdo->prepare($sql); $st->execute($args); $products=$st->fetchAll();
$cartCount=array_sum($_SESSION['cart']??[]);
?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>TokoKita — Belanja mudah</title><link rel="stylesheet" href="assets/style.css"></head><body><?php $emojis=['🎧','👜','💡','🧴','🥤','⌚','🛋️','👟','🕯️','🧘']; ?>
<header class="top"><a class="brand" href="index.php"><span class="brand-icon">K</span> toko<span>kita</span></a><form class="search" method="get"><input name="q" value="<?=e($q)?>" placeholder="Cari produk favoritmu..."><button>⌕</button></form><a class="cart-link" href="cart.php">🛒 Keranjang <b><?=e($cartCount)?></b></a></header>
<nav class="nav"><a class="<?=!$cat?'selected':''?>" href="index.php">Semua</a><?php foreach($cats as $c):?><a class="<?=$cat===$c['id']?'selected':''?>" href="?kategori=<?=$c['id']?>"><?=e($c['name'])?></a><?php endforeach?></nav>
<main><section class="hero"><div><span class="eyebrow">PILIHAN TERBAIK UNTUKMU</span><h1>Belanja nyaman,<br><em>hidup lebih mudah.</em></h1><p>Temukan produk berkualitas dengan harga bersahabat. Semua kebutuhan, dalam satu tempat.</p><a class="btn" href="#produk">Jelajahi produk <span>→</span></a></div><div class="hero-art"><div class="sun"></div><div class="bag">🛍️</div><div class="spark s1">✦</div><div class="spark s2">✧</div></div></section>
<div class="section-head" id="produk"><div><span class="eyebrow">KURASI TOKOKITA</span><h2><?= $q ? 'Hasil pencarian' : ($cat?'Produk pilihan':'Produk unggulan')?></h2></div><span class="muted"><?=count($products)?> produk</span></div>
<?php if(!$products):?><div class="empty"><div>⌕</div><h3>Produk belum ditemukan</h3><p>Coba kata kunci atau kategori lain.</p><a href="index.php">Lihat semua produk</a></div><?php else:?><div class="grid"><?php foreach($products as $i=>$p):?><article class="card"><a class="product-image tone<?=$i%5?>" href="product.php?id=<?=$p['id']?>"><span class="badge"><?=e($p['category_name'])?></span><span class="emoji"><?=render_emoji($emojis[((int)$p['id']-1)%count($emojis)])?></span></a><div class="card-body"><div class="product-name"><a href="product.php?id=<?=$p['id']?>"><?=e($p['name'])?></a><span>★ 4.9</span></div><p><?=e($p['short_description'])?></p><div class="price-row"><strong>Rp <?=number_format($p['price'],0,',','.')?></strong><form method="post" action="cart.php"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>"><input type="hidden" name="product_id" value="<?=$p['id']?>"><input type="hidden" name="action" value="add"><button class="add" aria-label="Tambah ke keranjang">+</button></form></div></div></article><?php endforeach?></div><?php endif?></main>
<footer><a class="brand" href="index.php"><span class="brand-icon">K</span> toko<span>kita</span></a><span>Belanja cerdas dimulai dari sini.</span><span>© <?=date('Y')?> TokoKita</span></footer></body></html>