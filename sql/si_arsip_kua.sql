-- Database: si_arsip_kua
CREATE DATABASE IF NOT EXISTS si_arsip_kua CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE si_arsip_kua;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','petugas') NOT NULL DEFAULT 'petugas',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS akta_nikah (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nomor_akta VARCHAR(100) NOT NULL,
  tanggal_akad DATE NOT NULL,
  nama_pria VARCHAR(200) NOT NULL,
  nik_pria VARCHAR(30),
  ttl_pria VARCHAR(100),
  nama_ayah_pria VARCHAR(200),
  nama_wanita VARCHAR(200) NOT NULL,
  nik_wanita VARCHAR(30),
  ttl_wanita VARCHAR(100),
  nama_ayah_wanita VARCHAR(200),
  alamat_kua VARCHAR(255),
  file_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_nama_pria ON akta_nikah (nama_pria);
CREATE INDEX idx_nama_wanita ON akta_nikah (nama_wanita);
CREATE INDEX idx_nomor_akta ON akta_nikah (nomor_akta);
CREATE INDEX idx_tanggal_akad ON akta_nikah (tanggal_akad);

CREATE TABLE IF NOT EXISTS log_activity (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_ip VARCHAR(50),
  activity VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Insert default admin (password = admin123) change in production
INSERT INTO users (username, password, name, role) VALUES
('admin', '$2y$10$fkGJnMPNW5NYFUQ7iBof2OqbsPrbdzWG7iiOvdfP2ykseZNdh0L1a', 'admin');
