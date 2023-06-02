-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1
-- 生成日期： 2023-06-01 08:50:49
-- 服务器版本： 8.0.29
-- PHP 版本： 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `laravel`
--

-- --------------------------------------------------------

--
-- 表的结构 `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `admins`
--

INSERT INTO `admins` (`id`, `email`, `full_name`, `token`, `password`, `create_time`, `created_at`, `updated_at`) VALUES
(1, 'admin@eaphoto.com', 'admin', NULL, '$2y$10$Pc0aSHyfgRgvot64bk72Tejwac.b3o8LE902TJCZj2wOrk/vRmOjm', '2023-06-01 06:06', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, 'admin3@eaphoto.com', 'admin', NULL, '$2y$10$bRkq4y9Ocphd7m4Z4bwHsOHOtL2h9jEN0H.bQKwyS6OFnFSH81eoC', '2023-06-01 06:06', '2023-05-31 22:47:21', '2023-05-31 22:47:21');

-- --------------------------------------------------------

--
-- 表的结构 `frames`
--

CREATE TABLE `frames` (
  `id` bigint UNSIGNED NOT NULL,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `frames`
--

INSERT INTO `frames` (`id`, `url`, `price`, `name`, `size_id`, `created_at`, `updated_at`) VALUES
(1, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 550, 'black', 1, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(2, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 550, 'red', 1, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 980, 'black', 2, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(4, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 850, 'red', 2, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(5, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 520, 'black', 3, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(6, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 1150, 'red', 3, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(7, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 450, 'red', 4, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(8, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 5506, 'white', 4, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(9, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 2501, 'red', 5, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(10, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 6, 'green', 5, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(11, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 2220, 'blue', 6, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(12, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 1999, 'yellow', 6, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(13, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 771, 'red', 7, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(14, 'http://127.0.0.1/laravel/public/storage/mockup_1.jpg', 2888, 'pink', 7, '2023-05-31 22:47:21', '2023-05-31 22:47:21');

-- --------------------------------------------------------

--
-- 表的结构 `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(167, '2023_05_15_072923_create_admins_table', 1),
(168, '2023_05_16_010552_create_sizes_table', 1),
(169, '2023_05_16_015946_create_frames_table', 1),
(170, '2023_05_16_022513_create_orders_table', 1),
(171, '2023_05_16_024946_create_users_table', 1),
(172, '2023_05_16_060304_create_photos_table', 1);

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_on_card` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `exp_date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cvv` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total` int NOT NULL,
  `order_placed` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `orders`
--

INSERT INTO `orders` (`id`, `full_name`, `phone_number`, `shipping_address`, `card_number`, `name_on_card`, `exp_date`, `cvv`, `total`, `order_placed`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Matthew', '10001000', 'Where', '3223222222', 'Matthew XXX', '2023-05-16', '246', 0, '2023-05-16 13:58', 'Valid', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(2, 'Matthew', '10001000', 'Where', '3223222222', 'Matthew XXX', '2023-05-16', '246', 0, '2023-05-16 13:58', 'Valid', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, 'Matthew', '10001000', 'Where', '3223222222', 'Matthew XXX', '2023-05-16', '246', 0, '2023-05-16 13:58', 'Valid', '2023-05-31 22:47:21', '2023-05-31 22:47:21');

-- --------------------------------------------------------

--
-- 表的结构 `photos`
--

CREATE TABLE `photos` (
  `id` bigint UNSIGNED NOT NULL,
  `edited_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `original_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `framed_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `frame_id` bigint UNSIGNED DEFAULT NULL,
  `size_id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `photos`
--

INSERT INTO `photos` (`id`, `edited_url`, `original_url`, `framed_url`, `frame_id`, `size_id`, `user_id`, `order_id`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/zXR9Hzw3zofnvW8XvFmnl8jfz9KwnUTKB8FWFsRw.png', 2, 2, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(2, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/zXR9Hzw3zofnvW8XvFmnl8jfz9KwnUTKB8FWFsRw.png', 2, 1, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/zXR9Hzw3zofnvW8XvFmnl8jfz9KwnUTKB8FWFsRw.png', 2, 2, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(4, NULL, 'http://127.0.0.1/laravel/public/storage/BYb4sP9MO9I123gkYLcDN4DnR3rqW6tVqk6mQIwW.jpg', NULL, NULL, 1, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(5, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(6, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, 1, 'order', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(7, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, 2, 'order', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(8, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, NULL, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(9, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, 2, 'order', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(10, NULL, 'http://127.0.0.1/laravel/public/storage/Y5tuCM6soFEUfu8h1XtGb2HjFRVBIFqPS2KbPajE.jpg', 'http://127.0.0.1/laravel/public/storage/AstzYRuDeS4Lwge5kbvwEpEG9fEkaXHSft2vvWnq.png', 2, 2, 1, 1, 'order', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(11, NULL, 'http://127.0.0.1/laravel/public/storage/3S7f778XPkaZGVdkIOlLGVuveG9e9YkyQNXrAVDm.jpg', NULL, 1, 1, 1, 1, 'uploaded', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(12, NULL, 'http://127.0.0.1/laravel/public/storage/3S7f778XPkaZGVdkIOlLGVuveG9e9YkyQNXrAVDm.jpg', NULL, 1, 1, 1, 3, 'cart', '2023-05-31 22:47:21', '2023-05-31 22:47:21');

-- --------------------------------------------------------

--
-- 表的结构 `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint UNSIGNED NOT NULL,
  `size` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `width` int NOT NULL,
  `height` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `sizes`
--

INSERT INTO `sizes` (`id`, `size`, `width`, `height`, `price`, `created_at`, `updated_at`) VALUES
(1, '1 Inch', 3, 4, 10, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(2, '2 Inch', 3, 5, 15, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, '3 Inch', 6, 8, 60, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(4, '5 Inch', 9, 13, 70, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(5, '6 Inch', 10, 15, 100, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(6, '7 Inch', 13, 18, 120, '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(7, '8 Inch', 15, 20, 120, '2023-05-31 22:47:21', '2023-05-31 22:47:21');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_total` int DEFAULT NULL,
  `create_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `token`, `cart_total`, `create_time`, `created_at`, `updated_at`) VALUES
(1, 'user@eaphoto.com', 'sample_user', '$2y$10$qX6H8MnIq.TF/ujCjqhQPeRt1wLcgkOZ0kKdPxzQrMfG2Y.H1buku', NULL, 300, '2023-05-16 11:12', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(2, 'us3er@eaphoto.com', 'sample_user', '$2y$10$5UsKHm.rGZUZbaTTGUSgDeVRdWE.TdgHvz5EH19BHAONa6/piSLQ6', NULL, 300, '2023-05-16 11:12', '2023-05-31 22:47:21', '2023-05-31 22:47:21'),
(3, 'us4er@eaphoto.com', 'sample_user', '$2y$10$QNBCA.zHg/tWRb96sEgJ5.Z7ljx9Sapq4bdRNUXQ1EkyKUkWWyBxK', NULL, 300, '2023-05-16 11:12', '2023-05-31 22:47:21', '2023-05-31 22:47:21');

--
-- 转储表的索引
--

--
-- 表的索引 `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `frames`
--
ALTER TABLE `frames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `frames_size_id_foreign` (`size_id`);

--
-- 表的索引 `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `photos_frame_id_foreign` (`frame_id`),
  ADD KEY `photos_size_id_foreign` (`size_id`),
  ADD KEY `photos_order_id_foreign` (`order_id`);

--
-- 表的索引 `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `frames`
--
ALTER TABLE `frames`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=173;

--
-- 使用表AUTO_INCREMENT `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `photos`
--
ALTER TABLE `photos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- 使用表AUTO_INCREMENT `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- 限制导出的表
--

--
-- 限制表 `frames`
--
ALTER TABLE `frames`
  ADD CONSTRAINT `frames_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);

--
-- 限制表 `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_frame_id_foreign` FOREIGN KEY (`frame_id`) REFERENCES `frames` (`id`),
  ADD CONSTRAINT `photos_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `photos_size_id_foreign` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
