CREATE DATABASE IF NOT EXISTS erp_praktikum CHARACTER SET utf8mb4;
USE erp_praktikum;
DROP TABLE IF EXISTS jurnal, akun, material, pr, permintaan, master_data;
CREATE TABLE master_data(id INT AUTO_INCREMENT PRIMARY KEY,nama VARCHAR(60),dipakai VARCHAR(120),keterangan VARCHAR(150));
INSERT INTO master_data(nama,dipakai,keterangan) VALUES
('Pelanggan','Penjualan, Keuangan','Identitas, alamat, termin pembayaran'),
('Produk jadi','Penjualan, Produksi, Gudang','Kode, nama, harga jual, satuan'),
('Bahan baku','Gudang, Produksi, Pembelian','Kode, nama, satuan, lead time'),
('Bill of Materials (BOM)','Produksi, Gudang, Pembelian','Komposisi bahan per 1 produk'),
('Pemasok','Pembelian, Keuangan','Identitas, termin, lead time'),
('Gudang / lokasi','Gudang, Produksi','Kode lokasi penyimpanan'),
('Chart of Accounts','Keuangan, Penjualan, Pembelian','Daftar akun buku besar');
CREATE TABLE akun(kode VARCHAR(10) PRIMARY KEY,nama VARCHAR(50),tipe VARCHAR(20));
INSERT INTO akun VALUES('1101','Kas','aset'),('1102','Piutang Usaha','aset'),('1103','Persediaan','aset'),('2101','Utang Usaha','liabilitas'),('3101','Modal','ekuitas'),('4101','Penjualan','pendapatan'),('5101','Beban Operasional','beban');
CREATE TABLE jurnal(id INT AUTO_INCREMENT PRIMARY KEY,tanggal DATE,ket VARCHAR(120),debit VARCHAR(10),kredit VARCHAR(10),nilai BIGINT,ref_id INT NULL);
INSERT INTO jurnal(tanggal,ket,debit,kredit,nilai) VALUES
(CURDATE(),'Saldo awal kas','1101','3101',30000000),
(CURDATE(),'Pembelian bahan baku kredit','1103','2101',8000000),
(CURDATE(),'Pembayaran utang pemasok','2101','1101',3000000),
(CURDATE(),'Penjualan produk kredit','1102','4101',12000000),
(CURDATE(),'Penerimaan pembayaran pelanggan','1101','1102',5000000),
(CURDATE(),'Pembayaran biaya operasional tunai','5101','1101',1500000);
CREATE TABLE material(kode VARCHAR(5) PRIMARY KEY,nama VARCHAR(40),stok INT,po_open INT,lead_time INT,per_unit INT,satuan VARCHAR(10) DEFAULT 'unit');
INSERT INTO material VALUES('A','Bahan A',400,100,4,3,'unit'),('B','Bahan B',250,0,7,2,'unit');
CREATE TABLE pr(id INT AUTO_INCREMENT PRIMARY KEY,kode VARCHAR(5),qty INT,diterima INT DEFAULT 0,status VARCHAR(20) DEFAULT 'terbuka');
CREATE TABLE permintaan(id INT AUTO_INCREMENT PRIMARY KEY,pengaju VARCHAR(60),bahan VARCHAR(80),nilai BIGINT,nilai_setuju BIGINT DEFAULT 0,status VARCHAR(30) DEFAULT 'diajukan');
