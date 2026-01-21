-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 21 Oca 2026, 12:27:53
-- Sunucu sürümü: 10.4.22-MariaDB
-- PHP Sürümü: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `aaqr`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bolumler`
--

CREATE TABLE `bolumler` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bolum_adi` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aktif_mi` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `bolumler`
--

INSERT INTO `bolumler` (`id`, `bolum_adi`, `aktif_mi`, `created_at`, `updated_at`) VALUES
(1, 'Bilgisayar Mühendisliği', 1, NULL, '2026-01-20 21:24:22'),
(2, 'Elektrik-Elektronik Mühendisliği', 1, NULL, NULL),
(3, 'Endüstri Mühendisliği', 1, NULL, '2026-01-20 21:24:07'),
(4, 'Makine Mühendisliği', 1, NULL, NULL),
(5, 'İnşaat Mühendisliği', 1, '2026-01-21 11:03:23', '2026-01-21 11:03:23');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `cihazlar`
--

CREATE TABLE `cihazlar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kullanici_id` bigint(20) UNSIGNED NOT NULL,
  `cihaz_uuid` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etiket` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ilk_gorulme` datetime DEFAULT NULL,
  `son_gorulme` datetime DEFAULT NULL,
  `guvenilir_mi` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `cihazlar`
--

INSERT INTO `cihazlar` (`id`, `kullanici_id`, `cihaz_uuid`, `etiket`, `platform`, `user_agent`, `ilk_gorulme`, `son_gorulme`, `guvenilir_mi`, `created_at`, `updated_at`) VALUES
(1, 1, '60dce29cde85fd80e62d35e4be7e993918eb9c07', NULL, 'web', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', '2025-12-17 23:09:55', '2026-01-21 03:36:05', 0, '2025-12-17 20:09:55', '2026-01-21 00:36:05'),
(2, 1, '765cd7b075997456e80a85bcf9cc07e5b9bed185', NULL, 'web', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', '2025-12-17 23:18:07', '2025-12-18 15:57:25', 0, '2025-12-17 20:18:07', '2025-12-18 12:57:25'),
(3, 3, 'bffccc368ac13504864a22cfc35e24bd34bc4bda', NULL, 'web', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', '2025-12-18 14:02:05', '2025-12-18 14:02:05', 0, '2025-12-18 11:02:05', '2025-12-18 11:02:05'),
(4, 3, 'd34468d529772d75c2f35342bd01507f81e48cfb', NULL, 'web', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', '2026-01-21 00:34:09', '2026-01-21 00:34:29', 0, '2026-01-20 21:34:09', '2026-01-20 21:34:29'),
(5, 3, '671ddfc61e74fd480e5543b6d2be188b91d9bf24', NULL, 'web', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', '2026-01-21 14:06:51', '2026-01-21 14:14:55', 0, '2026-01-21 11:06:51', '2026-01-21 11:14:55');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `dersler`
--

CREATE TABLE `dersler` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `hoca_id` bigint(20) UNSIGNED NOT NULL,
  `ders_kodu` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ders_adi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `aciklama` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `dersler`
--

INSERT INTO `dersler` (`id`, `hoca_id`, `ders_kodu`, `ders_adi`, `aciklama`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, 'CSE101', 'Programlamaya Giriş', 'test', '2025-12-17 17:59:41', '2025-12-17 17:59:41', NULL),
(3, 2, 'KTU100', 'Yapay Zeka', 'Yapay Zeka', '2025-12-17 20:26:30', '2025-12-17 20:26:30', NULL),
(4, 2, 'VERİ100', 'Veri İletişimi', 'test', '2026-01-21 11:04:00', '2026-01-21 11:04:00', NULL),
(5, 2, 'CSE110', 'Yapısal Programlama', 'test', '2026-01-21 11:12:36', '2026-01-21 11:12:36', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_acilimlari`
--

CREATE TABLE `ders_acilimlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_id` bigint(20) UNSIGNED NOT NULL,
  `hoca_id` bigint(20) UNSIGNED DEFAULT NULL,
  `akademik_yil` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `donem` enum('guz','bahar','yaz') COLLATE utf8mb4_unicode_ci NOT NULL,
  `sube` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `aktif_mi` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `ders_acilimlari`
--

INSERT INTO `ders_acilimlari` (`id`, `ders_id`, `hoca_id`, `akademik_yil`, `donem`, `sube`, `aktif_mi`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 2, 2, '2024-2025', 'guz', 'İkinci Öğretim', 1, '2025-12-17 18:02:58', '2025-12-17 18:02:58', NULL),
(3, 3, 2, '2024-2025', 'guz', 'İÖ', 1, '2025-12-17 20:26:42', '2025-12-17 20:26:42', NULL),
(4, 4, 2, '2025-2026', 'guz', '1', 1, '2026-01-21 11:04:12', '2026-01-21 11:04:12', NULL),
(5, 4, 2, '2025-2026', 'bahar', '1', 1, '2026-01-21 11:04:24', '2026-01-21 11:04:24', NULL),
(6, 2, 2, '2025-2026', 'guz', '1', 1, '2026-01-21 11:12:54', '2026-01-21 11:12:54', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_davetleri`
--

CREATE TABLE `ders_davetleri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_acilim_id` bigint(20) UNSIGNED NOT NULL,
  `olusturan_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hedef_ogrenci_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hedef_eposta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `son_gecerlilik_tarihi` datetime DEFAULT NULL,
  `max_kullanim` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `kullanim_sayisi` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `aktif` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `ders_davetleri`
--

INSERT INTO `ders_davetleri` (`id`, `ders_acilim_id`, `olusturan_id`, `token`, `hedef_ogrenci_id`, `hedef_eposta`, `son_gecerlilik_tarihi`, `max_kullanim`, `kullanim_sayisi`, `aktif`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 'Bj1xSfHPs2WIr9BtuvAgtY0DiTaxQ384SdtRR6AzZYqX96kM', NULL, NULL, '2025-12-17 22:30:41', 1, 1, 0, '2025-12-17 18:40:41', '2025-12-17 20:48:11'),
(2, 3, 2, 'WWQpy51uW5HTk7d60j0KoTisKGX8XCIP8qYJ0BcmrVjHqRe8', NULL, NULL, '2025-12-18 00:17:03', 1, 1, 1, '2025-12-17 20:27:03', '2025-12-17 20:27:09'),
(3, 2, 2, 'NruCKX0Gg4yKuYxtKeu4SYcZ4GPEMZcwEI4baPw6DSZVveUL', NULL, NULL, NULL, 1, 1, 1, '2025-12-17 20:48:14', '2025-12-17 20:48:29'),
(4, 3, 2, 'XLJeVjWsE53cRobm0cHMnP440zxzqzrhYCuxNeZGIaLAB4Lh', NULL, NULL, NULL, 1, 1, 1, '2025-12-18 11:00:33', '2025-12-18 11:01:03'),
(5, 4, 2, 'TLSXKw6YWYjQw7HPx8196S5WlS7y1wwjBl9ETuZiYuDynPbm', NULL, NULL, '2026-01-21 14:34:58', 1, 1, 1, '2026-01-21 11:04:58', '2026-01-21 11:05:29'),
(6, 5, 2, 'Vh33CQ8YKnKClyAl1yTq470tzI9yqgZo3fdVNWDWxJ3mMRnf', NULL, NULL, '2026-01-21 14:43:34', 1, 1, 1, '2026-01-21 11:13:34', '2026-01-21 11:13:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_kayitlari`
--

CREATE TABLE `ders_kayitlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_acilim_id` bigint(20) UNSIGNED NOT NULL,
  `ogrenci_id` bigint(20) UNSIGNED NOT NULL,
  `durum` enum('aktif','birakti') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `kayit_sekli` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ogrenci',
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `ders_kayitlari`
--

INSERT INTO `ders_kayitlari` (`id`, `ders_acilim_id`, `ogrenci_id`, `durum`, `kayit_sekli`, `kayit_tarihi`, `created_at`, `updated_at`) VALUES
(4, 3, 1, 'aktif', 'davet', '2025-12-17 23:27:09', '2025-12-17 20:27:09', '2025-12-17 20:27:09'),
(6, 3, 3, 'aktif', 'davet', '2025-12-18 14:01:03', '2025-12-18 11:01:03', '2025-12-18 11:01:03'),
(10, 4, 1, 'aktif', 'hoca', '2026-01-21 11:04:48', '2026-01-21 11:04:48', '2026-01-21 11:04:48'),
(11, 4, 3, 'aktif', 'davet', '2026-01-21 11:05:29', '2026-01-21 11:05:29', '2026-01-21 11:05:29'),
(12, 5, 1, 'aktif', 'hoca', '2026-01-21 11:13:24', '2026-01-21 11:13:24', '2026-01-21 11:13:24'),
(13, 5, 3, 'aktif', 'davet', '2026-01-21 11:13:56', '2026-01-21 11:13:56', '2026-01-21 11:13:56');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ders_oturumlari`
--

CREATE TABLE `ders_oturumlari` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_acilim_id` bigint(20) UNSIGNED NOT NULL,
  `oturum_tarihi` date NOT NULL,
  `baslangic_zamani` datetime DEFAULT NULL,
  `bitis_zamani` datetime DEFAULT NULL,
  `baslik` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hoca_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `ders_oturumlari`
--

INSERT INTO `ders_oturumlari` (`id`, `ders_acilim_id`, `oturum_tarihi`, `baslangic_zamani`, `bitis_zamani`, `baslik`, `hoca_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '2025-12-17', '2025-12-17 22:33:47', NULL, 'Test', 2, '2025-12-17 19:33:47', '2025-12-17 19:33:47', NULL),
(2, 2, '2025-12-17', '2025-12-17 23:06:19', NULL, NULL, 2, '2025-12-17 20:06:19', '2025-12-17 20:06:19', NULL),
(3, 3, '2025-12-18', '2025-12-18 14:01:29', NULL, 'Test', 2, '2025-12-18 11:01:29', '2025-12-18 11:01:29', NULL),
(4, 2, '2025-12-18', '2025-12-18 15:54:12', NULL, 'Test', 2, '2025-12-18 12:54:12', '2025-12-18 12:54:12', NULL),
(5, 3, '2025-12-18', '2025-12-18 15:56:24', NULL, NULL, 2, '2025-12-18 12:56:24', '2025-12-18 12:56:24', NULL),
(6, 2, '2026-01-21', '2026-01-21 00:30:54', NULL, 'eren', 2, '2026-01-20 21:30:54', '2026-01-20 21:30:54', NULL),
(7, 3, '2026-01-21', '2026-01-21 03:37:44', NULL, 'aaaaaa', 2, '2026-01-21 00:37:44', '2026-01-21 00:37:44', NULL),
(8, 5, '2026-01-21', '2026-01-21 14:05:57', NULL, 'Çarşamba', 2, '2026-01-21 11:05:57', '2026-01-21 11:05:57', NULL),
(9, 5, '2026-01-21', '2026-01-21 14:05:58', NULL, 'Çarşamba', 2, '2026-01-21 11:05:58', '2026-01-21 11:05:58', NULL),
(10, 5, '2026-01-21', '2026-01-21 14:14:14', NULL, 'Önemli Konular', 2, '2026-01-21 11:14:14', '2026-01-21 11:14:14', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

CREATE TABLE `kullanicilar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ad_soyad` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eposta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sifre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('ogrenci','hoca','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ogrenci',
  `bolum_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ogrenci_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `personel_no` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif_mi` tinyint(1) NOT NULL DEFAULT 1,
  `eposta_dogrulandi_at` timestamp NULL DEFAULT NULL,
  `hatirla_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `kullanicilar`
--

INSERT INTO `kullanicilar` (`id`, `ad_soyad`, `eposta`, `sifre`, `rol`, `bolum_id`, `ogrenci_no`, `personel_no`, `aktif_mi`, `eposta_dogrulandi_at`, `hatirla_token`, `created_at`, `updated_at`) VALUES
(1, 'Mustafa Eren Biricik', 'qeren1998@gmail.com', '$2y$12$x201T6BikytFH7mmViqIfeMHIOlt.6U7OZdE6yWNR6pE8oN1PQAuW', 'ogrenci', 1, '213255067', NULL, 1, NULL, NULL, '2025-12-17 15:19:49', '2025-12-17 15:19:49'),
(2, 'Berra Biricik', 'berrabiricik@gmail.com', '$2y$12$x201T6BikytFH7mmViqIfeMHIOlt.6U7OZdE6yWNR6pE8oN1PQAuW', 'hoca', 1, NULL, '21546231', 1, NULL, NULL, NULL, '2026-01-20 20:56:36'),
(3, 'Aziz Barış Üzümcü', 'abaris@kku.edu.tr', '$2y$12$x201T6BikytFH7mmViqIfeMHIOlt.6U7OZdE6yWNR6pE8oN1PQAuW', 'ogrenci', 1, '213255051', NULL, 1, NULL, NULL, '2025-12-18 10:59:43', '2025-12-18 10:59:43'),
(4, 'Adem çeltil', 'semihcltk@gmail.com', '$2y$12$u.5ACWsIyEggG8zyZB/Ttudg0a6oqkHEKFtdzeNGhdsJFGjAm7ic6', 'ogrenci', 1, '190255027', NULL, 1, NULL, NULL, '2025-12-18 12:57:31', '2025-12-18 12:57:31'),
(5, 'Eren Biricik', 'qeren1999@gmail.com', '$2y$12$x201T6BikytFH7mmViqIfeMHIOlt.6U7OZdE6yWNR6pE8oN1PQAuW', 'admin', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL),
(6, 'Enes Ayan', 'enesayan@gmail.com', '$2y$12$x201T6BikytFH7mmViqIfeMHIOlt.6U7OZdE6yWNR6pE8oN1PQAuW', 'hoca', 1, NULL, '23165432', 1, NULL, NULL, '2026-01-20 21:06:54', '2026-01-20 21:06:54'),
(7, 'Recep Biricik', 'recepbiricik@gmail.com', '$2y$12$98aP8esxYC/l7Kn8PIB31O72j33aurcBysRlCY5A3LJRmTfYWkak6', 'hoca', 2, NULL, '222222', 1, NULL, NULL, '2026-01-21 11:02:42', '2026-01-21 11:02:42'),
(8, 'Sema Biricik', 'semabiricik@gmail.com', '$2y$12$fJfd0Pl2H4oTTPDlntaYY.CMajYDdHDhnC.HQq7G5.vZFqsYFatP6', 'hoca', 1, NULL, '21323', 1, NULL, NULL, '2026-01-21 11:11:07', '2026-01-21 11:11:07');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_13_22395_create_kullanicilar_table', 2),
(5, '2025_12_13_223955_create_dersler_table', 3),
(6, '2025_12_13_2239566_create_ders_acilimlari_table', 3),
(7, '2025_12_13_223956_create_ders_kayitlari_table', 3),
(8, '2025_12_13_223956_create_ders_oturumlari_table', 3),
(9, '2025_12_13_223956_create_yoklama_pencereleri_table', 3),
(10, '2025_12_13_223957_create_cihazlar_table', 3),
(11, '2025_12_13_223957_create_yoklamalar_table', 4),
(12, '2025_12_13_223957_create_yoklama_supheleri_table', 5),
(13, '2025_12_17_205118_derslere_hoca_id_ve_unique_ekle', 6),
(14, '2025_12_17_205721_derslerden_olusturan_hoca_id_kaldir', 7),
(15, '2025_12_17_210619_create_ders_davetleri_table', 8),
(16, '2025_12_17_210709_ders_acilimlarina_kayit_modu_ekle', 9),
(17, '2025_12_17_211245_ders_acilimlarindan_kayit_modu_kaldir', 10),
(18, '2025_12_17_211255_ders_kayitlarina_kayit_sekli_ekle', 10),
(19, '2026_01_20_233642_bolumler', 11),
(20, '2026_01_20_233713_2026_01_21_000002_kullanicilara_bolum_id_ekle', 11);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('E96e0Z51qNjV8GusWVdEOExtSfETbhDEKuaXgxGi', 1, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYWpwVlJ6clhJazJ5QVNmOUdCdmVjZzByYk5ack53Y2xzNjVDRXl5TyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xOTIuMTY4LjEuMjo4MDAxL3Byb2ZpbCI7czo1OiJyb3V0ZSI7czoxMzoicHJvZmlsLmdvc3RlciI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1768994182),
('ndv7yRzznH1qDz3SjndjnOtx58wOYL9IUa0FxDay', 3, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGZrUk1RSWNBRXZ3bTdMOHAxZG5TbGVjWHhXV3I1ZDJwZkJleEN3MyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo3ODoiaHR0cDovLzE5Mi4xNjguMS4yOjgwMDEvZGF2ZXQvVExTWEt3NllXWWpRdzdIUHg4MTk2UzVXbFM3eTF3d2pCbDlFVHVaaVl1RHluUGJtIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xOTIuMTY4LjEuMjo4MDAxL29ncmVuY2kvcGFuZWwiO3M6NToicm91dGUiO3M6MTM6Im9ncmVuY2kucGFuZWwiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1768993529),
('Tu4wHVgHeot2gq96LbvzDh1aSHYhT2XJICHT6SK0', 5, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 YaBrowser/25.12.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWkxaVnQ5ODlHQ21BaENJTHlEbXQ5elgwTjRGSGllQjdtY2tvVzl5YyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDQ6Imh0dHA6Ly8xOTIuMTY4LjEuMjo4MDAxL2FkbWluL2hvY2FsYXIvNi9lZGl0IjtzOjU6InJvdXRlIjtzOjE4OiJhZG1pbi5ob2NhbGFyLmVkaXQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1768993934),
('XLpuPYyukbxDD7hqJGxS4QvhwY9qHgMyrzFVJPsb', 2, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUnFFZmwwNDdNc0JPVEFGc3g3Snh0a2hLbGJRN2hGbFhnbUR2MGt1RSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xOTIuMTY4LjEuMjo4MDAxL2hvY2EvcGFuZWwiO3M6NToicm91dGUiO3M6MTA6ImhvY2EucGFuZWwiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1768994382);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yoklamalar`
--

CREATE TABLE `yoklamalar` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_oturum_id` bigint(20) UNSIGNED NOT NULL,
  `ogrenci_id` bigint(20) UNSIGNED NOT NULL,
  `yoklama_pencere_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cihaz_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_adresi` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `durum` enum('katildi','gec_kaldi','manuel','supheli','reddedildi') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'katildi',
  `isaretlenme_zamani` datetime NOT NULL,
  `qr_adim_no` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `yoklamalar`
--

INSERT INTO `yoklamalar` (`id`, `ders_oturum_id`, `ogrenci_id`, `yoklama_pencere_id`, `cihaz_id`, `ip_adresi`, `user_agent`, `durum`, `isaretlenme_zamani`, `qr_adim_no`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 9, 2, '212.252.23.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', 'supheli', '2025-12-17 23:49:05', 0, '2025-12-17 20:09:55', '2025-12-17 20:49:05'),
(2, 1, 1, 8, 2, '212.252.23.34', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', 'katildi', '2025-12-17 23:18:07', 0, '2025-12-17 20:18:07', '2025-12-17 20:18:07'),
(3, 3, 3, 10, 3, '37.155.240.179', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Mobile Safari/537.36', 'supheli', '2025-12-18 14:02:05', 2, '2025-12-18 11:02:05', '2025-12-18 11:02:05'),
(4, 5, 1, 12, 2, '5.46.54.210', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_6_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/143.0.7499.108 Mobile/15E148 Safari/604.1', 'supheli', '2025-12-18 15:57:25', 5, '2025-12-18 12:56:42', '2025-12-18 12:57:25'),
(5, 6, 1, 15, 1, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'supheli', '2026-01-21 03:36:05', 0, '2026-01-20 21:31:28', '2026-01-21 00:36:05'),
(6, 6, 3, 14, 4, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:146.0) Gecko/20100101 Firefox/146.0', 'supheli', '2026-01-21 00:34:29', 0, '2026-01-20 21:34:09', '2026-01-20 21:34:29'),
(7, 9, 3, 16, 5, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'supheli', '2026-01-21 14:07:00', 0, '2026-01-21 11:06:51', '2026-01-21 11:07:00'),
(8, 10, 3, 17, 5, '192.168.1.2', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'supheli', '2026-01-21 14:14:55', 1, '2026-01-21 11:14:55', '2026-01-21 11:14:55');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yoklama_pencereleri`
--

CREATE TABLE `yoklama_pencereleri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ders_oturum_id` bigint(20) UNSIGNED NOT NULL,
  `acani_hoca_id` bigint(20) UNSIGNED DEFAULT NULL,
  `baslangic_zamani` datetime NOT NULL,
  `bitis_zamani` datetime NOT NULL,
  `yenileme_saniye` tinyint(3) UNSIGNED NOT NULL DEFAULT 10,
  `gizli_anahtar` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `durum` enum('acik','kapali','sure_doldu') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'acik',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `yoklama_pencereleri`
--

INSERT INTO `yoklama_pencereleri` (`id`, `ders_oturum_id`, `acani_hoca_id`, `baslangic_zamani`, `bitis_zamani`, `yenileme_saniye`, `gizli_anahtar`, `durum`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2025-12-17 22:39:59', '2025-12-17 22:40:59', 20, 'K19SOD52ZSWZWvMGJQ9GMvGnVDRIK7gZWP8nHw5ePmU22hONJj58t6dcSj8Ww7l0', 'kapali', '2025-12-17 19:39:59', '2025-12-17 19:40:05'),
(2, 1, 2, '2025-12-17 22:40:05', '2025-12-17 22:45:05', 10, '8o1vgOwo95hsNUm5uh6FjiPAyoskGnbLqZ7U2eiSNd8Sw1wvQZiY8RQ3jIxse9lH', 'kapali', '2025-12-17 19:40:05', '2025-12-17 19:41:28'),
(3, 1, 2, '2025-12-17 22:41:28', '2025-12-17 22:46:28', 10, 'BscEWhOGJg7a3uD9sGpTs77iLs5fDLZ0r6GYa34CycrhD3p4opofRLT7BatL0cvq', 'kapali', '2025-12-17 19:41:28', '2025-12-17 19:42:03'),
(4, 1, 2, '2025-12-17 22:44:04', '2025-12-17 22:45:04', 10, 'TFJl7E9BvN2kcPuADWyCUSOhf89GJCSNYV8N6N0dVc2UaxcbfP6uG0cFNkleDTSK', 'sure_doldu', '2025-12-17 19:44:04', '2025-12-17 19:45:04'),
(5, 1, 2, '2025-12-17 22:47:04', '2025-12-17 22:48:04', 10, 'WAtz75lDtwneKyxBIlXFQGq8KB37QxZCPDsXo3kefd1UwAHZFdzg7ehtbIdPwgbP', 'kapali', '2025-12-17 19:47:04', '2025-12-17 19:47:21'),
(6, 1, 2, '2025-12-17 23:00:02', '2025-12-17 23:01:02', 10, '2f9bcY3NbYt8zi3kXom6vmFXJkekMM5rKZorYpghlkKNrGp5JHz1ITojvOmkHd8c', 'sure_doldu', '2025-12-17 20:00:02', '2025-12-17 20:01:02'),
(7, 2, 2, '2025-12-17 23:07:00', '2025-12-17 23:12:00', 20, 'rUAQrIk5dPJOShJsdc0YE4L7ZtddKpJWfhJhqhxmh8mEs54kOGYT3ImiFQBBXy3Z', 'sure_doldu', '2025-12-17 20:07:00', '2025-12-17 20:12:01'),
(8, 1, 2, '2025-12-17 23:18:00', '2025-12-17 23:19:00', 10, 'CvwqMnArkqXqTw7tdW5nbvUMOqVVpQQ56QgA5L5jDfKU5FKaPcgdgRigZ8R2iaDJ', 'kapali', '2025-12-17 20:18:00', '2025-12-17 20:18:36'),
(9, 2, 2, '2025-12-17 23:48:52', '2025-12-17 23:50:52', 15, 'YTh1Ntpf5kdt14M8j9Hyay3gSWHvWcb2TApo7HJpuSD1yrG1fAe792CnJGR9h1Eg', 'sure_doldu', '2025-12-17 20:48:52', '2025-12-17 20:50:52'),
(10, 3, 2, '2025-12-18 14:01:33', '2025-12-18 14:02:33', 10, 'yNm5tQ3NWKnkuMS72XR3dYgfqJE5Fe7rwqXbX05qCQkunlTvVMjeAVy57qeGPFs0', 'kapali', '2025-12-18 11:01:33', '2025-12-18 11:02:24'),
(11, 4, 2, '2025-12-18 15:54:26', '2025-12-18 15:55:26', 10, 'FcDIS5nTC8iblpqLcycH0iwL62umbsCWdqF7GEc9YTJlqgcCmBKJiDj0I2y4BDUI', 'sure_doldu', '2025-12-18 12:54:26', '2025-12-18 12:55:26'),
(12, 5, 2, '2025-12-18 15:56:30', '2025-12-18 15:57:30', 10, 'd8tqGjo4d3AICi5kBO4MAlrmNtEh5WvjEua9Wr3dgF6BxfehrykkuLWFEBLD76aa', 'sure_doldu', '2025-12-18 12:56:30', '2025-12-18 12:57:31'),
(13, 6, 2, '2026-01-21 00:31:01', '2026-01-21 00:32:01', 10, 'WqBdW7oAvxgTu2ZsbAiG70NF9UXbBF9z16QyaOEXQ9hZWZyh3M085rKEp6V1l5K5', 'sure_doldu', '2026-01-20 21:31:01', '2026-01-20 21:32:01'),
(14, 6, 2, '2026-01-21 00:33:54', '2026-01-21 00:49:54', 20, 'YY3x5LzAebiwyy5NUplcIzJmK1U1EhHiobEyDSnCqRp3f6c89IwgZivJXCa3gGD5', 'kapali', '2026-01-20 21:33:54', '2026-01-20 21:34:43'),
(15, 6, 2, '2026-01-21 03:35:30', '2026-01-21 03:36:30', 10, 'DJY9MsXtmeuVYC1Mn2fu54jcntjVOxMm23Cjm1dN47CPopx2LvNlK0eDr1GHTnOj', 'sure_doldu', '2026-01-21 00:35:30', '2026-01-21 00:36:31'),
(16, 9, 2, '2026-01-21 14:06:42', '2026-01-21 14:07:42', 10, 'WhXqQb6l2HqdLhCcCkoKBiFyOc8jHjUau9wRFGtkuTgOYI76oTuBNRkRWrLBJBZ8', 'kapali', '2026-01-21 11:06:42', '2026-01-21 11:07:28'),
(17, 10, 2, '2026-01-21 14:14:35', '2026-01-21 14:15:35', 10, '4nIpNjmRaFQhobpMH6pM4JEix4qhyPqDPsUwB40x2yO7lTaJDlJ2C4icuJmHzpQI', 'kapali', '2026-01-21 11:14:35', '2026-01-21 11:15:26'),
(18, 10, 2, '2026-01-21 14:15:43', '2026-01-21 14:16:43', 10, 'hnRC11Zx4IZ4l1mTZGZV3oBQAyg0D0rmCUOdPdVu9ZJTXFxcheHqObMbKLVLpj3W', 'acik', '2026-01-21 11:15:43', '2026-01-21 11:15:43');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yoklama_supheleri`
--

CREATE TABLE `yoklama_supheleri` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `yoklama_id` bigint(20) UNSIGNED NOT NULL,
  `tur` enum('yeni_cihaz','ayni_oturum_coklu_cihaz','ip_uyusmazligi','qr_tekrar') COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `yoklama_supheleri`
--

INSERT INTO `yoklama_supheleri` (`id`, `yoklama_id`, `tur`, `meta`, `created_at`, `updated_at`) VALUES
(1, 1, 'yeni_cihaz', '{\"cihaz_id\":1,\"uuid\":\"60dce29cde85fd80e62d35e4be7e993918eb9c07\",\"guvenilir_mi\":false}', '2025-12-17 20:09:55', '2025-12-17 20:09:55'),
(2, 1, 'qr_tekrar', '{\"onceki_adim\":10,\"yeni_adim\":10,\"server_adim\":11}', '2025-12-17 20:10:46', '2025-12-17 20:10:46'),
(3, 1, 'qr_tekrar', '{\"onceki_adim\":10,\"yeni_adim\":10,\"server_adim\":11}', '2025-12-17 20:10:48', '2025-12-17 20:10:48'),
(4, 1, 'qr_tekrar', '{\"onceki_adim\":10,\"yeni_adim\":10,\"server_adim\":11}', '2025-12-17 20:10:50', '2025-12-17 20:10:50'),
(5, 1, 'qr_tekrar', '{\"onceki_adim\":10,\"yeni_adim\":10,\"server_adim\":11}', '2025-12-17 20:10:50', '2025-12-17 20:10:50'),
(6, 2, 'yeni_cihaz', '{\"cihaz_id\":2,\"uuid\":\"765cd7b075997456e80a85bcf9cc07e5b9bed185\",\"guvenilir_mi\":false}', '2025-12-17 20:18:07', '2025-12-17 20:18:07'),
(7, 1, 'ayni_oturum_coklu_cihaz', '{\"onceki_cihaz_id\":1,\"yeni_cihaz_id\":2}', '2025-12-17 20:49:05', '2025-12-17 20:49:05'),
(8, 1, 'qr_tekrar', '{\"onceki_adim\":10,\"yeni_adim\":0,\"server_adim\":0}', '2025-12-17 20:49:05', '2025-12-17 20:49:05'),
(9, 3, 'yeni_cihaz', '{\"cihaz_id\":3,\"uuid\":\"bffccc368ac13504864a22cfc35e24bd34bc4bda\",\"guvenilir_mi\":false}', '2025-12-18 11:02:05', '2025-12-18 11:02:05'),
(10, 3, 'qr_tekrar', '{\"client_adim\":2,\"server_adim\":3}', '2025-12-18 11:02:05', '2025-12-18 11:02:05'),
(11, 4, 'yeni_cihaz', '{\"cihaz_id\":2,\"uuid\":\"765cd7b075997456e80a85bcf9cc07e5b9bed185\",\"guvenilir_mi\":false}', '2025-12-18 12:56:42', '2025-12-18 12:56:42'),
(12, 4, 'qr_tekrar', '{\"client_adim\":0,\"server_adim\":1}', '2025-12-18 12:56:42', '2025-12-18 12:56:42'),
(13, 5, 'yeni_cihaz', '{\"cihaz_id\":1,\"uuid\":\"60dce29cde85fd80e62d35e4be7e993918eb9c07\",\"guvenilir_mi\":false}', '2026-01-20 21:31:28', '2026-01-20 21:31:28'),
(14, 6, 'yeni_cihaz', '{\"cihaz_id\":4,\"uuid\":\"d34468d529772d75c2f35342bd01507f81e48cfb\",\"guvenilir_mi\":false}', '2026-01-20 21:34:09', '2026-01-20 21:34:09'),
(15, 5, 'qr_tekrar', '{\"onceki_adim\":2,\"yeni_adim\":0,\"server_adim\":1}', '2026-01-20 21:34:14', '2026-01-20 21:34:14'),
(16, 6, 'qr_tekrar', '{\"onceki_adim\":0,\"yeni_adim\":0,\"server_adim\":1}', '2026-01-20 21:34:29', '2026-01-20 21:34:29'),
(17, 5, 'qr_tekrar', '{\"onceki_adim\":0,\"yeni_adim\":0,\"server_adim\":0}', '2026-01-21 00:35:35', '2026-01-21 00:35:35'),
(18, 5, 'qr_tekrar', '{\"onceki_adim\":0,\"yeni_adim\":0,\"server_adim\":3}', '2026-01-21 00:36:05', '2026-01-21 00:36:05'),
(19, 7, 'yeni_cihaz', '{\"cihaz_id\":5,\"uuid\":\"671ddfc61e74fd480e5543b6d2be188b91d9bf24\",\"guvenilir_mi\":false}', '2026-01-21 11:06:52', '2026-01-21 11:06:52'),
(20, 7, 'qr_tekrar', '{\"onceki_adim\":0,\"yeni_adim\":0,\"server_adim\":1}', '2026-01-21 11:07:00', '2026-01-21 11:07:00'),
(21, 8, 'yeni_cihaz', '{\"cihaz_id\":5,\"uuid\":\"671ddfc61e74fd480e5543b6d2be188b91d9bf24\",\"guvenilir_mi\":false}', '2026-01-21 11:14:55', '2026-01-21 11:14:55'),
(22, 8, 'qr_tekrar', '{\"client_adim\":1,\"server_adim\":2}', '2026-01-21 11:14:55', '2026-01-21 11:14:55');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `bolumler`
--
ALTER TABLE `bolumler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bolumler_bolum_adi_unique` (`bolum_adi`);

--
-- Tablo için indeksler `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Tablo için indeksler `cihazlar`
--
ALTER TABLE `cihazlar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cihazlar_cihaz_uuid_unique` (`cihaz_uuid`),
  ADD KEY `cihazlar_kullanici_id_foreign` (`kullanici_id`),
  ADD KEY `cihazlar_guvenilir_mi_index` (`guvenilir_mi`);

--
-- Tablo için indeksler `dersler`
--
ALTER TABLE `dersler`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_hoca_ders_kodu` (`hoca_id`,`ders_kodu`),
  ADD KEY `dersler_hoca_id_index` (`hoca_id`);

--
-- Tablo için indeksler `ders_acilimlari`
--
ALTER TABLE `ders_acilimlari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ders_acilim_uniq` (`ders_id`,`akademik_yil`,`donem`,`sube`),
  ADD KEY `ders_acilimlari_hoca_id_foreign` (`hoca_id`),
  ADD KEY `ders_acilimlari_akademik_yil_index` (`akademik_yil`),
  ADD KEY `ders_acilimlari_donem_index` (`donem`);

--
-- Tablo için indeksler `ders_davetleri`
--
ALTER TABLE `ders_davetleri`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ders_davetleri_token_unique` (`token`),
  ADD KEY `ders_davetleri_ders_acilim_id_foreign` (`ders_acilim_id`),
  ADD KEY `ders_davetleri_olusturan_id_foreign` (`olusturan_id`),
  ADD KEY `ders_davetleri_hedef_ogrenci_id_foreign` (`hedef_ogrenci_id`);

--
-- Tablo için indeksler `ders_kayitlari`
--
ALTER TABLE `ders_kayitlari`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ders_kayit_uniq` (`ders_acilim_id`,`ogrenci_id`),
  ADD KEY `ders_kayitlari_ogrenci_id_foreign` (`ogrenci_id`),
  ADD KEY `ders_kayitlari_durum_index` (`durum`);

--
-- Tablo için indeksler `ders_oturumlari`
--
ALTER TABLE `ders_oturumlari`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ders_oturumlari_olusturan_hoca_id_foreign` (`hoca_id`),
  ADD KEY `oturum_arama_idx` (`ders_acilim_id`,`oturum_tarihi`),
  ADD KEY `ders_oturumlari_oturum_tarihi_index` (`oturum_tarihi`);

--
-- Tablo için indeksler `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Tablo için indeksler `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Tablo için indeksler `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kullanicilar_eposta_unique` (`eposta`),
  ADD UNIQUE KEY `kullanicilar_ogrenci_no_unique` (`ogrenci_no`),
  ADD UNIQUE KEY `kullanicilar_personel_no_unique` (`personel_no`),
  ADD KEY `kullanicilar_rol_index` (`rol`),
  ADD KEY `kullanicilar_bolum_id_index` (`bolum_id`);

--
-- Tablo için indeksler `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Tablo için indeksler `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Tablo için indeksler `yoklamalar`
--
ALTER TABLE `yoklamalar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `oturum_ogrenci_uniq` (`ders_oturum_id`,`ogrenci_id`),
  ADD KEY `yoklamalar_ogrenci_id_foreign` (`ogrenci_id`),
  ADD KEY `yoklamalar_yoklama_pencere_id_foreign` (`yoklama_pencere_id`),
  ADD KEY `yoklamalar_cihaz_id_foreign` (`cihaz_id`),
  ADD KEY `yoklama_oturum_durum_idx` (`ders_oturum_id`,`durum`),
  ADD KEY `yoklamalar_durum_index` (`durum`),
  ADD KEY `yoklamalar_isaretlenme_zamani_index` (`isaretlenme_zamani`);

--
-- Tablo için indeksler `yoklama_pencereleri`
--
ALTER TABLE `yoklama_pencereleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yoklama_pencereleri_acani_hoca_id_foreign` (`acani_hoca_id`),
  ADD KEY `pencere_oturum_durum_idx` (`ders_oturum_id`,`durum`),
  ADD KEY `yoklama_pencereleri_durum_index` (`durum`);

--
-- Tablo için indeksler `yoklama_supheleri`
--
ALTER TABLE `yoklama_supheleri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yoklama_supheleri_yoklama_id_foreign` (`yoklama_id`),
  ADD KEY `yoklama_supheleri_tur_index` (`tur`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `bolumler`
--
ALTER TABLE `bolumler`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `cihazlar`
--
ALTER TABLE `cihazlar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `dersler`
--
ALTER TABLE `dersler`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `ders_acilimlari`
--
ALTER TABLE `ders_acilimlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `ders_davetleri`
--
ALTER TABLE `ders_davetleri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Tablo için AUTO_INCREMENT değeri `ders_kayitlari`
--
ALTER TABLE `ders_kayitlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Tablo için AUTO_INCREMENT değeri `ders_oturumlari`
--
ALTER TABLE `ders_oturumlari`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Tablo için AUTO_INCREMENT değeri `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `kullanicilar`
--
ALTER TABLE `kullanicilar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Tablo için AUTO_INCREMENT değeri `yoklamalar`
--
ALTER TABLE `yoklamalar`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Tablo için AUTO_INCREMENT değeri `yoklama_pencereleri`
--
ALTER TABLE `yoklama_pencereleri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Tablo için AUTO_INCREMENT değeri `yoklama_supheleri`
--
ALTER TABLE `yoklama_supheleri`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `cihazlar`
--
ALTER TABLE `cihazlar`
  ADD CONSTRAINT `cihazlar_kullanici_id_foreign` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `dersler`
--
ALTER TABLE `dersler`
  ADD CONSTRAINT `dersler_hoca_id_foreign` FOREIGN KEY (`hoca_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `ders_acilimlari`
--
ALTER TABLE `ders_acilimlari`
  ADD CONSTRAINT `ders_acilimlari_ders_id_foreign` FOREIGN KEY (`ders_id`) REFERENCES `dersler` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ders_acilimlari_hoca_id_foreign` FOREIGN KEY (`hoca_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `ders_davetleri`
--
ALTER TABLE `ders_davetleri`
  ADD CONSTRAINT `ders_davetleri_ders_acilim_id_foreign` FOREIGN KEY (`ders_acilim_id`) REFERENCES `ders_acilimlari` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ders_davetleri_hedef_ogrenci_id_foreign` FOREIGN KEY (`hedef_ogrenci_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ders_davetleri_olusturan_id_foreign` FOREIGN KEY (`olusturan_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `ders_kayitlari`
--
ALTER TABLE `ders_kayitlari`
  ADD CONSTRAINT `ders_kayitlari_ders_acilim_id_foreign` FOREIGN KEY (`ders_acilim_id`) REFERENCES `ders_acilimlari` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ders_kayitlari_ogrenci_id_foreign` FOREIGN KEY (`ogrenci_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `ders_oturumlari`
--
ALTER TABLE `ders_oturumlari`
  ADD CONSTRAINT `ders_oturumlari_ders_acilim_id_foreign` FOREIGN KEY (`ders_acilim_id`) REFERENCES `ders_acilimlari` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ders_oturumlari_olusturan_hoca_id_foreign` FOREIGN KEY (`hoca_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `kullanicilar`
--
ALTER TABLE `kullanicilar`
  ADD CONSTRAINT `kullanicilar_bolum_id_foreign` FOREIGN KEY (`bolum_id`) REFERENCES `bolumler` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `yoklamalar`
--
ALTER TABLE `yoklamalar`
  ADD CONSTRAINT `yoklamalar_cihaz_id_foreign` FOREIGN KEY (`cihaz_id`) REFERENCES `cihazlar` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `yoklamalar_ders_oturum_id_foreign` FOREIGN KEY (`ders_oturum_id`) REFERENCES `ders_oturumlari` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yoklamalar_ogrenci_id_foreign` FOREIGN KEY (`ogrenci_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `yoklamalar_yoklama_pencere_id_foreign` FOREIGN KEY (`yoklama_pencere_id`) REFERENCES `yoklama_pencereleri` (`id`) ON DELETE SET NULL;

--
-- Tablo kısıtlamaları `yoklama_pencereleri`
--
ALTER TABLE `yoklama_pencereleri`
  ADD CONSTRAINT `yoklama_pencereleri_acani_hoca_id_foreign` FOREIGN KEY (`acani_hoca_id`) REFERENCES `kullanicilar` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `yoklama_pencereleri_ders_oturum_id_foreign` FOREIGN KEY (`ders_oturum_id`) REFERENCES `ders_oturumlari` (`id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `yoklama_supheleri`
--
ALTER TABLE `yoklama_supheleri`
  ADD CONSTRAINT `yoklama_supheleri_yoklama_id_foreign` FOREIGN KEY (`yoklama_id`) REFERENCES `yoklamalar` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
