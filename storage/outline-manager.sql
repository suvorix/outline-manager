-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Мар 09 2026 г., 15:13
-- Версия сервера: 5.7.44-48
-- Версия PHP: 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `u1735546_outline`
--

-- --------------------------------------------------------

--
-- Структура таблицы `key_statistics`
--

CREATE TABLE `key_statistics` (
  `id` int(11) NOT NULL,
  `key_id` int(11) NOT NULL COMMENT 'server_keys.id',
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время проверки',
  `tunnelTime` bigint(20) DEFAULT NULL COMMENT 'Суммарное количество секунд подключения для этого ключа',
  `dataTransferred` bigint(20) DEFAULT NULL COMMENT 'Суммарное количество байт, переданное по данному ключу',
  `lastTrafficSeen` datetime DEFAULT NULL COMMENT 'Когда в последний раз был зафиксирован трафик по этому ключу',
  `peakDeviceCount` int(11) DEFAULT NULL COMMENT 'Максимальное количество одновременно подключенных устройств для этого ключа'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `key_statistics`
--

INSERT INTO `key_statistics` (`id`, `key_id`, `date_add`, `tunnelTime`, `dataTransferred`, `lastTrafficSeen`, `peakDeviceCount`) VALUES
(7, 19, '2026-03-09 14:10:01', 300, 8513373, '2026-03-09 14:10:00', 2),
(8, 19, '2026-03-09 14:15:02', 300, NULL, NULL, 2),
(9, 19, '2026-03-09 14:20:02', 299, NULL, NULL, 1),
(10, 19, '2026-03-09 14:25:02', 299, NULL, '2026-03-09 14:25:00', 2),
(11, 19, '2026-03-09 14:30:02', 300, 41551035, '2026-03-09 14:30:00', 2),
(12, 19, '2026-03-09 14:35:02', 300, 41551035, '2026-03-09 14:35:00', 1),
(13, 19, '2026-03-09 14:40:03', 300, 14593496, '2026-03-09 14:40:00', 2),
(14, 19, '2026-03-09 14:45:01', 300, 14593496, '2026-03-09 14:45:00', 1),
(15, 19, '2026-03-09 14:50:02', 299, NULL, NULL, 1),
(16, 19, '2026-03-09 14:55:01', 299, NULL, '2026-03-09 14:55:00', 1),
(17, 19, '2026-03-09 15:05:01', NULL, NULL, '2026-03-09 15:05:00', 1),
(18, 19, '2026-03-09 15:10:02', 299, 1183547, '2026-03-09 15:10:00', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `servers`
--

CREATE TABLE `servers` (
  `id` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `name` varchar(100) NOT NULL,
  `apiUrl` varchar(100) NOT NULL,
  `certSha256` varchar(64) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `status_date_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `key_limit` int(11) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `servers`
--

INSERT INTO `servers` (`id`, `date_add`, `name`, `apiUrl`, `certSha256`, `status`, `status_date_update`, `key_limit`) VALUES
(9, '2025-11-16 19:27:22', 'Outline сервер 2', 'https://10.12.23.15:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2026-03-09 14:50:01', 20),
(11, '2025-11-16 19:27:22', 'Outline сервер', 'https://74.23.43.1:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2026-03-09 14:50:01', -1),
(21, '2025-11-23 11:01:59', 'reg ru 1', 'https://89.104.67.226:11417/dyzefzBv-4LR1lknCfnwMQ', 'D3AD4D67D1655E570AC565A6092E7523284FC01FAED4A4A08262ED0D86683F60', -1, '2026-03-09 14:50:01', -1),
(26, '2025-12-13 17:09:23', 'Сервер 2 [immers.cloud]', 'https://195.209.210.25:3680/7tD8GmNAM8btQtlK7d4dYQ', 'D2309FEF83AA5D565B7494A9D26075751CD6627EA63673182225D192F1E4FAF6', -1, '2026-03-09 14:43:01', -1),
(27, '2026-02-28 14:35:31', 'Сервер 3 [immers.cloud]', 'https://195.209.218.63:12529/koO_SnZE13FlBP2tuB7O6Q', '39422DD1189E8B891D662A077DF921107E80F28A5F05626327524EFE8FCD03B6', -1, '2026-03-09 14:38:02', -1),
(28, '2026-03-01 09:26:19', 'Сервер 5 [immers.cloud]', 'https://195.209.218.172:58928/eYaHNoDV0wclrECDbmHGDw', '51F8B0A1C9BE92A1CEC954BF6A5139DC18C42333DC8437CB408B36B8CA984F80', -1, '2026-03-09 15:00:02', -1),
(29, '2026-03-01 17:17:37', 'Сервер 6 [immers.cloud]', 'https://195.209.219.54:24905/cfTeNaHbCzwix3x02hj6wA', '2EA02FC54A9C1A2D50D4AE7CF30DF6D6FB7E74BAA3FDAA596483F563283D1FDE', -1, '2026-03-09 14:38:02', -1),
(30, '2026-03-08 17:07:28', 'Сервер 7 [immers.cloud]', 'https://195.209.218.126:25980/rv9_bghN8aMg0OMrcaRKqg', '1B0F626FA6AF12E745D92D073B5907BC9C26E854857226F53B32599D9E96DF98', -1, '2026-03-09 15:03:02', -1),
(31, '2026-03-09 12:33:56', 'Сервер 8 [immers.cloud]', 'https://195.209.210.133:10311/z8htsVYBDGPB16m2gsOEng', '7ABD410FD92151C568391D5FF85116F41BADD70B7FE5D85A97B993D731A0E7CA', -1, '2026-03-09 15:11:01', -1);

-- --------------------------------------------------------

--
-- Структура таблицы `server_keys`
--

CREATE TABLE `server_keys` (
  `id` int(11) NOT NULL,
  `date_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_end` date DEFAULT NULL,
  `server_id` int(11) NOT NULL COMMENT 'servers.id',
  `key_id` varchar(255) NOT NULL,
  `key_name` varchar(255) NOT NULL,
  `key_password` varchar(255) NOT NULL,
  `key_port` int(11) NOT NULL,
  `key_method` varchar(255) NOT NULL,
  `key_accessUrl` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `server_keys`
--

INSERT INTO `server_keys` (`id`, `date_add`, `date_end`, `server_id`, `key_id`, `key_name`, `key_password`, `key_port`, `key_method`, `key_accessUrl`) VALUES
(1, '2025-12-13 17:45:14', NULL, 26, '3', '', 'rmTFwPOqgmVrfEbLKv4L8w', 17537, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpybVRGd1BPcWdtVnJmRWJMS3Y0TDh3@195.209.210.25:17537/?outline=1'),
(2, '2025-12-13 17:46:28', NULL, 26, '4', '', 'K9Fz5Q51LcmFAtBz6LxBkG', 17537, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpLOUZ6NVE1MUxjbUZBdEJ6Nkx4QmtH@195.209.210.25:17537/?outline=1'),
(3, '2025-12-13 17:46:48', NULL, 26, '5', '', 'TVIvfdxIDSC761kMK3xHUG', 17537, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpUVkl2ZmR4SURTQzc2MWtNSzN4SFVH@195.209.210.25:17537/?outline=1'),
(4, '2026-02-28 14:37:19', NULL, 27, '1', '', '9icmD1DZQHdVyZZs3s9NgH', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTo5aWNtRDFEWlFIZFZ5WlpzM3M5TmdI@195.209.218.63:43281/?outline=1'),
(5, '2026-02-28 14:44:45', NULL, 27, '2', '', '1L9o9NChqhDtzozJfUJX1F', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNToxTDlvOU5DaHFoRHR6b3pKZlVKWDFG@195.209.218.63:43281/?outline=1'),
(6, '2026-02-28 15:00:12', NULL, 0, '3', '', 'c6od98Lozl8pJpQl30YahL', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpjNm9kOThMb3psOHBKcFFsMzBZYWhM@195.209.218.63:43281/?outline=1'),
(7, '2026-02-28 15:00:35', NULL, 27, '4', '', 'r3OFgNjjBVBbSF8QEunOi2', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpyM09GZ05qakJWQmJTRjhRRXVuT2ky@195.209.218.63:43281/?outline=1'),
(12, '2026-02-28 15:18:37', '2026-03-01', 27, '10', 'test 123', 'mPorxWibTxTJeEIGvbR1Gi', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTptUG9yeFdpYlR4VEplRUlHdmJSMUdp@195.209.218.63:43281/?outline=1'),
(13, '2026-02-28 15:18:50', '2026-03-01', 27, '11', 'test 123', 'yXn7CZogHELxAvplgetfXg', 43281, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTp5WG43Q1pvZ0hFTHhBdnBsZ2V0Zlhn@195.209.218.63:43281/?outline=1'),
(14, '2026-03-01 09:26:40', NULL, 28, '1', '', 'FoiRt8a5H0TwT1OpgtAyGM', 30110, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpGb2lSdDhhNUgwVHdUMU9wZ3RBeUdN@195.209.218.172:30110/?outline=1'),
(15, '2026-03-01 09:52:17', '2026-03-02', 28, '2', 'проверка 1', 'ninIYCwv6RJ8m987qyk7hD', 30110, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpuaW5JWUN3djZSSjhtOTg3cXlrN2hE@195.209.218.172:30110/?outline=1'),
(16, '2026-03-01 17:17:51', NULL, 29, '1', 'gh2', 'NdHMG87ApUew0V1mTEG4fL', 45399, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpOZEhNRzg3QXBVZXcwVjFtVEVHNGZM@195.209.219.54:45399/?outline=1'),
(17, '2026-03-08 17:09:32', NULL, 30, '3', 'phone', 'VzENfIpoO5Jg64UFO0ogZ8', 44661, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpWekVOZklwb081Smc2NFVGTzBvZ1o4@195.209.218.126:44661/?outline=1'),
(18, '2026-03-08 17:10:47', NULL, 30, '4', 'pc', 'SCWt3gcQxAd2LZ5AvNvKF0', 44661, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpTQ1d0M2djUXhBZDJMWjVBdk52S0Yw@195.209.218.126:44661/?outline=1'),
(19, '2026-03-09 12:35:00', NULL, 31, '1', 'phone', 'EuvbUsvQP5v3cwPXb0QkZ4', 21581, 'chacha20-ietf-poly1305', 'ss://Y2hhY2hhMjAtaWV0Zi1wb2x5MTMwNTpFdXZiVXN2UVA1djNjd1BYYjBRa1o0@195.209.210.133:21581/?outline=1');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `key_statistics`
--
ALTER TABLE `key_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `server_keys`
--
ALTER TABLE `server_keys`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `key_statistics`
--
ALTER TABLE `key_statistics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT для таблицы `server_keys`
--
ALTER TABLE `server_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
