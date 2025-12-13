-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 13 2025 г., 15:54
-- Версия сервера: 5.7.44-48
-- Версия PHP: 8.2.28

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
(8, '2025-11-16 19:27:22', 'Outline сервер', 'https://1.2.3.4:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', 25),
(9, '2025-11-16 19:27:22', 'Outline сервер 2', 'https://10.12.23.15:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', 20),
(11, '2025-11-16 19:27:22', 'Outline сервер', 'https://74.23.43.1:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', -1),
(12, '2025-11-16 19:27:22', 'Outline сервер', 'https://10.10.10.1:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', -1),
(13, '2025-11-16 19:27:22', 'Моё название сервера', 'https://15.15.15.2:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', -1),
(16, '2025-11-16 19:27:22', 'Outline сервер 3', 'https://xxx.xxx.xxx.xxx:xxxxx/xxxxxxxxxxxxxxxxxxxxxx', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx', -1, '2025-12-13 15:52:11', 50),
(21, '2025-11-23 11:01:59', 'reg ru 1', 'https://89.104.67.226:11417/dyzefzBv-4LR1lknCfnwMQ', 'D3AD4D67D1655E570AC565A6092E7523284FC01FAED4A4A08262ED0D86683F60', -1, '2025-12-13 15:52:11', -1),
(22, '2025-12-06 15:32:17', 'Outline сервер', 'https://83.166.246.134:7381/SGXD6MkT3KY58HlUHOYXZQ', 'A85FE5D7EE585B6DC1DF854278B6AE448D571DCB4547B266B80004D220F5065F', -1, '2025-12-13 15:52:01', -1),
(25, '2025-12-13 13:20:17', 'Сервер [immers.cloud]', 'https://195.209.214.74:7747/xHLw2cQfIt3AHGuxim1_ag', '8F2547008CD52F6CC4FC02804E189FF197DA52F6497E43ECCBAD94D97F695ECA', -1, '2025-12-13 15:30:02', -1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `servers`
--
ALTER TABLE `servers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `servers`
--
ALTER TABLE `servers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
