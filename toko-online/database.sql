CREATE DATABASE IF NOT EXISTS tokokita_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tokokita_shop;
CREATE TABLE IF NOT EXISTS categories (id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(80) NOT NULL,slug VARCHAR(90) NOT NULL UNIQUE) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS products (id INT AUTO_INCREMENT PRIMARY KEY,category_id INT NOT NULL,name VARCHAR(140) NOT NULL,slug VARCHAR(160) NOT NULL UNIQUE,short_description VARCHAR(255) NOT NULL,description TEXT NOT NULL,price DECIMAL(12,2) NOT NULL,stock INT NOT NULL DEFAULT 0,emoji VARCHAR(20) DEFAULT '📦',image_url VARCHAR(500) DEFAULT '',featured TINYINT(1) NOT NULL DEFAULT 0,active TINYINT(1) NOT NULL DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,FOREIGN KEY(category_id) REFERENCES categories(id)) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS orders (id BIGINT AUTO_INCREMENT PRIMARY KEY,order_number VARCHAR(30) NOT NULL UNIQUE,customer_name VARCHAR(120) NOT NULL,email VARCHAR(190) NOT NULL,phone VARCHAR(30) NOT NULL,address TEXT NOT NULL,city VARCHAR(100) NOT NULL,postal_code VARCHAR(15) NOT NULL,subtotal DECIMAL(12,2) NOT NULL,shipping DECIMAL(12,2) NOT NULL,total DECIMAL(12,2) NOT NULL,status ENUM('Menunggu pembayaran','Diproses','Dikirim','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu pembayaran',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS order_items (id BIGINT AUTO_INCREMENT PRIMARY KEY,order_id BIGINT NOT NULL,product_id INT NOT NULL,product_name VARCHAR(140) NOT NULL,price DECIMAL(12,2) NOT NULL,quantity INT NOT NULL,FOREIGN KEY(order_id) REFERENCES orders(id),FOREIGN KEY(product_id) REFERENCES products(id)) ENGINE=InnoDB;
INSERT IGNORE INTO categories(id,name,slug) VALUES (1,'Elektronik','elektronik'),(2,'Fashion','fashion'),(3,'Rumah & Gaya Hidup','rumah-gaya-hidup'),(4,'Kecantikan','kecantikan'),(5,'Olahraga','olahraga');
INSERT IGNORE INTO products(category_id,name,slug,short_description,description,price,stock,emoji,featured) VALUES
(1,'Headphone Nirkabel Studio','headphone-nirkabel-studio','Suara jernih dengan kenyamanan sepanjang hari.','Headphone nirkabel dengan suara imersif, bantalan empuk, dan baterai tahan lama. Cocok untuk bekerja maupun bersantai.',429000,35,'🎧',1),
(2,'Tas Selempang Urban','tas-selempang-urban','Desain minimalis, teman setiap perjalanan.','Tas selempang ringan dengan banyak ruang penyimpanan dan bahan tahan percikan air.',289000,24,'👜',1),
(3,'Lampu Meja Awan','lampu-meja-awan','Cahaya hangat untuk ruang yang nyaman.','Lampu meja berdesain modern, tiga mode pencahayaan untuk membaca dan menemani waktu istirahat.',189000,42,'💡',1),
(4,'Serum Wajah Botanika','serum-wajah-botanika','Perawatan lembut untuk kulit bercahaya.','Serum harian dengan ekstrak tumbuhan, membantu menjaga kelembapan kulit. Isi 30 ml.',159000,50,'🧴',1),
(5,'Botol Minum Termal','botol-minum-termal','Segar lebih lama, dibawa ke mana saja.','Botol stainless steel berdinding ganda yang menjaga minuman tetap dingin atau hangat.',99000,60,'🥤',1),
(1,'Jam Tangan Pintar Lite','jam-tangan-pintar-lite','Pantau aktivitas dengan gaya modern.','Jam pintar dengan pemantau aktivitas, notifikasi, dan layar cerah untuk penggunaan sehari-hari.',599000,18,'⌚',1),
(3,'Bantal Sofa Nordic','bantal-sofa-nordic','Sentuhan lembut untuk sudut favoritmu.','Sarung kain lembut dengan isi empuk, cocok mempercantik sofa dan kamar tidur.',129000,32,'🛋️',0),
(2,'Sneaker Everyday','sneaker-everyday','Ringan dan nyaman untuk aktivitas harian.','Sneaker kasual dengan sol fleksibel dan bantalan nyaman. Tersedia dalam ukuran 36–44.',379000,20,'👟',0),
(4,'Lilin Aromaterapi Senja','lilin-aromaterapi-senja','Aroma menenangkan untuk waktu santai.','Lilin aromaterapi berbahan soy wax dengan aroma lembut. Waktu bakar hingga 35 jam.',89000,40,'🕯️',0),
(5,'Matras Yoga Flex','matras-yoga-flex','Stabil untuk latihan di rumah maupun studio.','Matras latihan antiselip, ringan, dan mudah dibawa. Termasuk tali pembawa.',219000,26,'🧘',0);
CREATE INDEX idx_products_category_active ON products(category_id,active);
CREATE INDEX idx_orders_created ON orders(created_at);

-- Import melalui phpMyAdmin: pilih tab Import lalu pilih berkas ini.
-- Login admin belum disertakan pada MVP; fitur katalog, keranjang, dan pesanan tersedia.