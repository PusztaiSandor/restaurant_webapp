-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Sze 15. 05:39
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `restaurant_webapp`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `bookings`
--

CREATE TABLE `bookings` (
  `bookings_id` bigint(20) UNSIGNED NOT NULL,
  `users_id` bigint(20) UNSIGNED NOT NULL,
  `orders_id` bigint(20) UNSIGNED DEFAULT NULL,
  `table_code` varchar(100) NOT NULL,
  `seats` int(11) NOT NULL,
  `booking_time` datetime NOT NULL,
  `status` enum('uj','teljesitve','elutasitva','torolve') NOT NULL DEFAULT 'uj',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `bookings`
--

INSERT INTO `bookings` (`bookings_id`, `users_id`, `orders_id`, `table_code`, `seats`, `booking_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 29, 'bj1,bb3', 6, '2025-08-22 17:42:14', 'torolve', '2025-08-22 13:43:30', '2025-08-22 14:25:18'),
(2, 1, 30, 'bb6', 8, '2025-08-24 12:45:53', 'teljesitve', '2025-08-24 08:46:56', '2025-08-26 12:28:26'),
(3, 1, 34, 'bb6', 8, '2025-08-25 19:49:27', 'teljesitve', '2025-08-25 15:50:42', '2025-08-25 15:52:57'),
(4, 1, 35, 'bj1,kk5', 8, '2025-08-26 12:10:40', 'elutasitva', '2025-08-26 08:12:01', '2025-08-26 12:28:49'),
(5, 1, 40, 'bb6', 8, '2025-08-26 20:58:46', 'teljesitve', '2025-08-26 17:53:55', '2025-08-27 07:08:03'),
(6, 1, 37, 'bj1,bb6', 12, '2025-08-26 19:25:42', 'torolve', '2025-08-26 18:10:44', '2025-08-26 18:11:40'),
(7, 1, 41, 'bj1', 4, '2025-08-26 22:14:54', 'torolve', '2025-08-26 18:15:11', '2025-08-26 18:16:21'),
(8, 1, 50, 'bj1,tb4', 8, '2025-09-08 21:01:15', 'uj', '2025-09-12 17:06:51', '2025-09-12 17:06:51'),
(9, 19, 56, 'bj1,tb4', 8, '2025-09-13 01:19:58', 'teljesitve', '2025-09-12 21:20:54', '2025-09-12 21:25:47'),
(10, 1, 57, 'kk5', 8, '2025-09-14 15:17:58', 'uj', '2025-09-14 11:23:15', '2025-09-14 11:23:15'),
(11, 19, 60, 'bk5', 8, '2025-09-14 22:06:58', 'uj', '2025-09-14 18:07:43', '2025-09-14 18:07:43');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `dishes`
--

CREATE TABLE `dishes` (
  `dishes_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `calories` int(11) DEFAULT NULL,
  `vegetarian` tinyint(1) NOT NULL DEFAULT 0,
  `gross_price` decimal(8,2) NOT NULL,
  `tax_percent` decimal(5,2) NOT NULL DEFAULT 27.00,
  `net_price` decimal(8,2) GENERATED ALWAYS AS (`gross_price` / (1 + `tax_percent` / 100)) STORED,
  `size_options` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`size_options`)),
  `ingredient_modifiers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`ingredient_modifiers`)),
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `allergens` text DEFAULT NULL,
  `base_ingredients` text DEFAULT NULL,
  `extra_ingredients` text DEFAULT NULL,
  `on_sale` tinyint(1) NOT NULL DEFAULT 0,
  `discount_percent` decimal(5,2) NOT NULL DEFAULT 0.00,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `dishes`
--

INSERT INTO `dishes` (`dishes_id`, `name`, `description`, `image`, `category`, `type`, `calories`, `vegetarian`, `gross_price`, `tax_percent`, `size_options`, `ingredient_modifiers`, `stock`, `allergens`, `base_ingredients`, `extra_ingredients`, `on_sale`, `discount_percent`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Római Saláta', 'Római saláta grillezett csirkemellel, parmezánnal és Caesar öntettel.', 'romai_salata.jpg', 'Étel', 'Saláta', 352, 0, 4000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":350,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":450,\"multiplier\":1.2}}', '{\"pir\\u00edtott keny\\u00e9rkocka\":200,\"extra \\u00f6ntet\":100,\"rukkola sal\\u00e1ta\":-100,\"parmez\\u00e1n sajt\":-200}', 91, '[\"tej\",\"toj\\u00e1s\",\"glut\\u00e9n\"]', '[\"rukkola sal\\u00e1ta\",\"parmez\\u00e1n sajt\"]', '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', 1, 10.00, 1, NULL, '2025-09-14 11:17:58'),
(2, 'Avokádó Pirítós', 'Pirított kenyér avokádókrémmel, lime-mal és chilivel.', 'avocado_toast.jpg', 'Vegetáriánus', 'Előétel', 190, 1, 2500.00, 27.00, '{\"Kicsi\":{\"unit\":\"szelet\",\"amount\":1,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"szelet\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"szelet\",\"amount\":3,\"multiplier\":1.2}}', '{\"toj\\u00e1s\":200,\"parmez\\u00e1n\":150,\"lime\":-50,\"chili\":-50}', 57, '[\"toj\\u00e1s\",\"tej\",\"glut\\u00e9n\"]', '[\"lime\",\"chili\"]', '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', 1, 10.00, 1, NULL, '2025-09-14 14:52:49'),
(3, 'Indiai Vajas Csirke', 'Indiai fűszeres csirke vajmártásban, basmati rizzsel.', 'butter_chicken.jpg', 'Nemzetközi', 'Egytálétel', 412, 0, 3000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":300,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":400,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":500,\"multiplier\":1.2}}', '{\"extra rizs\":200,\"koriander\":50,\"vaj\":-100,\"curry\":-50}', 46, '[\"tejfeh\\u00e9rje\",\"lakt\\u00f3z\"]', '[\"vaj\",\"curry\"]', '[\"extra rizs\",\"koriander\"]', 1, 10.00, 1, NULL, '2025-09-14 18:06:58'),
(4, 'Pirított Rizs Csirkével', 'Pirított rizs csirkével, zöldségekkel és szójaszósszal.', 'chicken_fried_rice.jpg', 'Étel', 'Egytálétel', 433, 0, 3000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":350,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":450,\"multiplier\":1.2}}', '{\"toj\\u00e1s\":150,\"sz\\u00f3jasz\\u00f3sz\":-100}', 48, '[\"glut\\u00e9n\",\"toj\\u00e1s\"]', '[\"sz\\u00f3jasz\\u00f3sz\"]', '[\"toj\\u00e1s\"]', 0, 0.00, 1, NULL, '2025-09-12 21:19:10'),
(5, 'Klasszikus Hamburger', 'Marhahúsos hamburger friss zöldségekkel és sajttal.', 'hamburger_classic.jpg', 'Étel', 'Hamburger', 280, 0, 3000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":180,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":320,\"multiplier\":1.2}}', '{\"sajt\":150,\"bacon\":200,\"sz\\u00f3sz\":-50,\"sal\\u00e1ta\":-50}', 46, '[\"glut\\u00e9n\",\"szez\\u00e1mmag\"]', '[\"sz\\u00f3sz\",\"sal\\u00e1ta\"]', '[\"sajt\",\"bacon\"]', 1, 10.00, 1, NULL, '2025-09-15 02:24:39'),
(6, 'Marhapörkölt Galuskával', 'Puhára főtt marhahús gazdag, paprikás szaftban, friss, házi galuskával.', 'marhaporkolt.jpg', 'Étel', 'Egytálétel', 500, 0, 5000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":300,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":400,\"multiplier\":1.2}}', '{\"cs\\u00edp\\u0151spaprika\":-200,\"chili\":200,\"petrezselyem\":100}', 50, '[\"h\\u00fas\",\"glut\\u00e9n\"]', '[\"cs\\u00edp\\u0151spaprika\"]', '[\"chili\",\"petrezselyem\"]', 1, 10.00, 1, '2025-08-12 16:31:09', '2025-08-30 10:07:13'),
(7, 'Thai Zöld Curry', 'Krémes kókusztejes curry friss zöldségekkel és thai fűszerekkel.', 'thai_zold_curry.jpg', 'Nemzetközi', 'Egytálétel', 520, 0, 3500.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":200,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":300,\"multiplier\":1.2}}', '{\"curry\":-100,\"k\\u00f3kusztej\":-100,\"extra z\\u00f6lds\\u00e9gek\":200}', 50, '[\"garn\\u00e9la\"]', '[\"curry\",\"k\\u00f3kusztej\"]', '[\"extra z\\u00f6lds\\u00e9gek\"]', 1, 10.00, 1, NULL, '2025-08-30 12:03:30'),
(8, 'Margherita Pizza', 'Klasszikus olasz pizza paradicsomszósszal, mozzarellával és bazsalikommal.', 'pizza_margherita.jpg', 'Vegetáriánus', 'Pizza', 680, 1, 3000.00, 27.00, '{\"Kicsi\":{\"unit\":\"cm\",\"amount\":20,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"cm\",\"amount\":28,\"multiplier\":1},\"Nagy\":{\"unit\":\"cm\",\"amount\":32,\"multiplier\":1.2}}', '{\"parmez\\u00e1n sajt\":200,\"rukkola\":100,\"paradicsom\":-100,\"bazsalikom\":-200}', 47, '[\"glut\\u00e9n\"]', '[\"paradicsom\",\"bazsalikom\"]', '[\"parmez\\u00e1n sajt\",\"rukkola\"]', 0, 0.00, 1, NULL, '2025-09-15 01:59:43'),
(9, 'Pho Leves', 'Vietnam klasszikus marhahúsos rizstésztalevese illatos fűszerekkel.', 'pho_leves.jpg', 'Nemzetközi', 'Leves', 450, 0, 2950.00, 27.00, '{\"Kicsi\":{\"unit\":\"t\\u00e1l\",\"amount\":0.8,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"t\\u00e1l\",\"amount\":1,\"multiplier\":1},\"Nagy\":{\"unit\":\"t\\u00e1l\",\"amount\":1.2,\"multiplier\":1.2}}', '{\"lime\":100,\"chili\":100,\"gy\\u00f6mb\\u00e9r\":-100,\"szegf\\u0171szeg\":-100}', 50, '[\"sz\\u00f3ja\"]', '[\"gy\\u00f6mb\\u00e9r\",\"szegf\\u0171szeg\"]', '[\"lime\",\"chili\"]', 1, 10.00, 1, NULL, '2025-08-30 12:04:37'),
(10, 'Matcha Latte', 'Japán zöld tea porból készült krémes ital tejjel.', 'matcha_latte.jpg', 'Ital', 'Tea', 120, 1, 1500.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":1.5,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1.2}}', '{\"m\\u00e9z\":150,\"n\\u00e1dcukor\":100,\"gy\\u00f6mb\\u00e9r\":-100,\"k\\u00f3kusztej\":-150}', 50, '[\"tejfeh\\u00e9rje\",\"lakt\\u00f3z\"]', '[\"gy\\u00f6mb\\u00e9r\",\"k\\u00f3kusztej\"]', '[\"m\\u00e9z\",\"n\\u00e1dcukor\"]', 0, 0.00, 1, NULL, '2025-08-30 12:05:05'),
(11, 'Gyümölcsös Smoothie', 'Friss gyümölcsökből készült vitaminban gazdag ital.', 'gyumolcsos_smoothie.jpg', 'Fitness', 'Gyümölcs', 180, 1, 2000.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":1.5,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1.2}}', '{\"ban\\u00e1n\":150,\"kiwi\":150,\"citrom\":-100,\"narancs\":-150}', 49, '[\"tejfeh\\u00e9rje\"]', '[\"citrom\",\"narancs\"]', '[\"ban\\u00e1n\",\"kiwi\"]', 1, 10.00, 1, NULL, '2025-09-14 13:53:55'),
(12, 'Brownie Vaníliafagylalttal', 'Meleg csokis brownie vaníliafagylalttal tálalva.', 'brownie_vaniliafagylalt.jpg', 'Desszert', 'Sütemény', 620, 1, 2500.00, 27.00, '{\"Kicsi\":{\"unit\":\"adag\",\"amount\":1,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"adag\",\"amount\":1.5,\"multiplier\":1},\"Nagy\":{\"unit\":\"adag\",\"amount\":2,\"multiplier\":1.2}}', '{\"gy\\u00fcm\\u00f6lcs\\u00f6ntet\":150,\"extra tejsz\\u00ednhab\":100,\"csoki\\u00f6ntet\":-150,\"tejsz\\u00ednhab\":-100}', 48, '[\"glut\\u00e9n\",\"tejfeh\\u00e9rje\"]', '[\"csoki\\u00f6ntet\",\"tejsz\\u00ednhab\"]', '[\"extra tejsz\\u00ednhab\",\"gy\\u00f6m\\u00f6lcs\\u00f6ntet\"]', 0, 0.00, 1, NULL, '2025-09-15 01:59:43'),
(13, 'Tépett Húsos Taco', 'Lassú tűzön sült sertéshús kukoricalepényben, friss zöldségekkel és lime-mal.', 'pulled_pork_tacos.jpg', 'Étel', 'Húsétel', 640, 0, 3000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":300,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":350,\"multiplier\":1.2}}', '{\"paradicsom\":150,\"extra sal\\u00e1ta\":100,\"majon\\u00e9z\":-150,\"lila hagyma\":-100}', 50, '[\"glut\\u00e9nsz\\u00f3ja\"]', '[\"majon\\u00e9z\",\"lila hagyma\"]', '[\"paradicsom\",\"extra sal\\u00e1ta\"]', 0, 10.00, 1, NULL, '2025-08-30 11:09:38'),
(14, 'Caprese Saláta', 'Friss paradicsom, mozzarella és bazsalikom olívaolajjal.', 'caprese_salata.jpg', 'Vegetáriánus', 'Saláta', 320, 1, 2000.00, 27.00, '{\"Kicsi\":{\"unit\":\"g\",\"amount\":150,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"g\",\"amount\":200,\"multiplier\":1},\"Nagy\":{\"unit\":\"g\",\"amount\":250,\"multiplier\":1.2}}', '{\"rukkola sal\\u00e1ta\":150,\"kokt\\u00e9l paradicsom\":150,\"paradicsom\":-150,\"bazsalikom\":-150}', 50, '[\"tejfeh\\u00e9rje\"]', '[\"paradicsom\",\"bazsalikom\"]', '[\"rukkola sal\\u00e1ta\",\"kokt\\u00e9l paradicsom\"]', 0, 10.00, 1, NULL, '2025-08-30 11:21:06'),
(15, 'Ramen Tonkotsu', 'Japán sertésalapú ramen tésztával, tojással és zöldhagymával.', 'ramen_tonkotsu.jpg', 'Nemzetközi', 'Leves', 720, 0, 3500.00, 27.00, '{\"Kicsi\":{\"unit\":\"t\\u00e1ny\\u00e9r\",\"amount\":1,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"t\\u00e1ny\\u00e9r\",\"amount\":1.2,\"multiplier\":1},\"Nagy\":{\"unit\":\"t\\u00e1ny\\u00e9r\",\"amount\":1.5,\"multiplier\":1.2}}', '{\"extra toj\\u00e1s\":150,\"z\\u00f6ldhagyma\":150,\"gy\\u00f6mb\\u00e9r\":-150,\"fokhagyma\":-150}', 50, '[\"sz\\u00f3ja\",\"toj\\u00e1s\",\"tejfeh\\u00e9rje\"]', '[\"gy\\u00f6mb\\u00e9r\",\"fokhagyma\"]', '[\"extra toj\\u00e1s\",\"z\\u00f6ldhagyma\"]', 1, 10.00, 1, NULL, '2025-08-30 12:07:01'),
(16, 'Limonádé Mentával', 'Frissítő citromos ital mentával és jéggel.', 'limonade_mentaval.jpg', 'Ital', 'Üdítő', 90, 1, 1000.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":1.5,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1.2}}', '{\"lime\":150,\"narancs\":150,\"citrom\":-50,\"menta\":-50}', 49, '[\"menta\",\"citrus\"]', '[\"citrom\",\"menta\"]', '[\"lime\",\"narancs\"]', 1, 10.00, 1, NULL, '2025-09-14 13:53:55'),
(17, 'Csirkés Burrito', 'Fűszeres csirkemell, rizs, bab és sajt tortillába tekerve.', 'csirkes_burrito.jpg', 'Étel', 'Húsétel', 780, 0, 3250.00, 27.00, '{\"Kicsi\":{\"unit\":\"darab\",\"amount\":1,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"darab\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"darab\",\"amount\":3,\"multiplier\":1.2}}', '{\"salsa sz\\u00f3sz\":150,\"reszelt sajt\":150,\"hagyma\":-100,\"sz\\u00f3ja sz\\u00f3sz\":-150}', 50, '[\"sz\\u00f3ja\"]', '[\"hagyma\",\"sz\\u00f3ja sz\\u00f3sz\"]', '[\"salsa sz\\u00f3sz\",\"reszelt sajt\"]', 1, 10.00, 1, NULL, '2025-09-04 16:31:34'),
(18, 'Epres Sajttorta', 'Klasszikus sajttorta friss eperrel és eperöntettel.', 'epres_cheesecake.jpg', 'Desszert', 'Sütemény', 560, 1, 2500.00, 27.00, '{\"Kicsi\":{\"unit\":\"szelet\",\"amount\":1,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"szelet\",\"amount\":2,\"multiplier\":1},\"Nagy\":{\"unit\":\"szelet\",\"amount\":3,\"multiplier\":1.2}}', '{\"csoki\\u00f6ntet\":150,\"extra tejsz\\u00ednhab\":150,\"eper\\u00f6ntet\":-100,\"tejsz\\u00ednhab\":-150}', 48, '[\"tejfeh\\u00e9rje\",\"lakt\\u00f3z\"]', '[\"eper\\u00f6ntet\",\"tejsz\\u00ednhab\"]', '[\"csoki\\u00f6ntet\",\"extra tejsz\\u00ednhab\"]', 0, 10.00, 1, NULL, '2025-09-15 01:59:43'),
(19, 'Espresso Macchiato', 'Erős eszpresszó egy kis tejhabbal a tetején – klasszikus olasz kávé.', 'espresso_macchiato.jpg', 'Ital', 'Kávé', 15, 1, 1000.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1.2}}', '{\"n\\u00e1dcukor\":150,\"extra tejsz\\u00ednhab\":150,\"tejsz\\u00edn\":-50,\"cukor\":-50}', 48, '[\"tejfeh\\u00e9rje\",\"\"]', '[\"tejsz\\u00edn\",\"cukor\"]', '[\"n\\u00e1dcukor\",\"extra tejsz\\u00ednhab\"]', 1, 10.00, 1, NULL, '2025-09-14 13:53:55'),
(20, 'Mojito Mocktail', 'Frissítő lime-menta ital szódával – alkoholmentes változat.', 'mojito_mocktail.jpg', 'Ital', 'Koktél', 90, 1, 1500.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1.2}}', '{\"citrom\":150,\"narancs\":150,\"lime\":-50,\"menta\":-50}', 50, '[\"\"]', '[\"lime\",\"menta\"]', '[\"citrom\",\"narancs\"]', 1, 10.00, 1, NULL, '2025-08-30 12:14:02'),
(21, 'Almás Jeges Tea', 'Alkoholmentes, enyhén édesített almás jeges tea gyerekeknek.', 'gyerek_ice_tea.jpg', 'Ital', 'Gyerekital', 60, 1, 1000.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1.2}}', '{\"extra almaszeletek\":150,\"lime\":150,\"cukor\":-50,\"almaszeletek\":-50}', 49, '[\"\"]', '[\"cukor\",\"almaszeletek\"]', '[\"extra almaszeletek\",\"lime\"]', 0, 10.00, 1, NULL, '2025-09-14 18:06:58'),
(22, 'Szénsavas Ásványvíz', 'Hűsítő, enyhén szénsavas ásványvíz palackból.', 'szensavas_asvanyviz.jpg', 'Ital', 'Ásványvíz', 0, 1, 750.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":5,\"multiplier\":1.2}}', NULL, 48, '[\"\"]', '[\"\"]', '[\"\"]', 1, 10.00, 1, NULL, '2025-09-15 02:24:39'),
(23, 'Jeges Earl Grey Tea', 'Klasszikus Earl Grey tea jegesen, citrommal és mézzel.', 'jeges_earl_grey.jpg', 'Ital', 'Tea', 40, 1, 1250.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":5,\"multiplier\":1.2}}', NULL, 49, '[\"\"]', '[\"\"]', '[\"\"]', 1, 10.00, 1, NULL, '2025-09-15 01:59:43'),
(24, 'Narancsos Smoothie', 'Friss narancs, banán és joghurt keveréke – igazi vitaminbomba.', 'narancsos_smoothie.jpg', 'Fitness', 'Gyümölcs', 180, 1, 2000.00, 27.00, '{\"Kicsi\":{\"unit\":\"dl\",\"amount\":2,\"multiplier\":0.8},\"Norm\\u00e1l\":{\"unit\":\"dl\",\"amount\":2.5,\"multiplier\":1},\"Nagy\":{\"unit\":\"dl\",\"amount\":3,\"multiplier\":1.2}}', '{\"citrom\":150,\"ban\\u00e1n\":150,\"alma\":-50,\"narancs\":-50}', 50, '[\"\"]', '[\"alma\",\"narancs\"]', '[\"citrom\",\"ban\\u00e1n\"]', 1, 10.00, 1, NULL, '2025-08-30 12:01:05');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` bigint(20) UNSIGNED NOT NULL,
  `users_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('rating','message') NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `users_id`, `type`, `rating`, `subject`, `content`, `created_at`, `updated_at`) VALUES
(1, 1, 'rating', 5, 'Étterem értékelése', 'Barátságos kiszolgálás, finom ételek és italok.', '2025-08-22 16:24:25', '2025-08-22 16:24:25'),
(2, 1, 'rating', 5, 'Étterem elhelyezkedése, megközelítése', 'Nagyon jó a közlekedési kapcsolat, kiváló az étterem elhelyezkedése.', '2025-08-23 19:26:30', '2025-08-23 19:26:30'),
(3, 1, 'rating', 4, 'Étterem tisztasága.', 'Az étteremhez vezető út síkos volt.', '2025-08-31 17:19:29', '2025-08-31 17:19:29'),
(4, 1, 'rating', 1, 'Étterem parkolójának a biztonsága.', 'Tegnap este megrongálták az autómat az étterem parkolójában, ez elfogadhatatlan, a javítási díjat az étteremre fogom terhelni.', '2025-08-31 18:10:40', '2025-08-31 18:10:40'),
(5, 1, 'rating', 4, 'Ez egy próba értékelés', 'Remélem, hogy négy csillag fog megjelenni.', '2025-08-31 18:19:35', '2025-08-31 18:19:35'),
(6, 1, 'rating', 4, 'Újabb próba a 4-esre', 'Újabb próba a 4-esre', '2025-08-31 18:27:23', '2025-08-31 18:27:23'),
(7, 1, 'rating', 4, 'Még mindig 4-es kellene', 'Még mindig 4-es kellene', '2025-08-31 18:33:02', '2025-08-31 18:33:02'),
(8, 1, 'rating', 2, 'próba 2-es', 'próba 2-es', '2025-08-31 18:37:13', '2025-08-31 18:37:13'),
(9, 1, 'rating', 5, 'Most 5 csillag kell', 'Most öt csillag kell', '2025-08-31 18:40:50', '2025-08-31 18:40:50'),
(10, 19, 'rating', 5, 'Étterem értékelése', 'Nagyon finom ételek és italok, gyors és pontos kiszállítás.', '2025-09-14 14:48:59', '2025-09-14 14:48:59'),
(11, 16, 'rating', 4, 'Étterem megközelítése', 'Az étterem megközelítése a parkoló lezárása miatt nehézkes.', '2025-09-14 15:59:43', '2025-09-14 15:59:43'),
(12, 6, 'rating', 5, 'Új terasz kialakítása', 'Az új terasz kialakítása nagyszerű lett.', '2025-09-14 16:02:00', '2025-09-14 16:02:00'),
(13, 1, 'rating', 4, 'Szervízdíj', 'A 10 százalékos szervízdíj egy kicsit magas.', '2025-09-14 16:11:17', '2025-09-14 16:11:17'),
(14, 22, 'rating', 4, 'Étterem értékelés', 'Az étterem kialakítása nagyszerű, de a légkondicionáló nagyon hidegre volt beállítva.', '2025-09-15 03:29:45', '2025-09-15 03:29:45'),
(15, 19, 'rating', 5, 'Étterem értékelése', 'Az étterem remek hangulatú, barátságos a kiszolgálás.', '2025-09-15 03:35:23', '2025-09-15 03:35:23');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `global_charges`
--

CREATE TABLE `global_charges` (
  `global_charges_id` bigint(20) UNSIGNED NOT NULL,
  `charge_type` enum('delivery_fee','service_fee','order_discount','cutlery') NOT NULL,
  `delivery_method` enum('delivery','dine-in','pickup') NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_percentage` tinyint(1) NOT NULL DEFAULT 1,
  `value` decimal(6,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_optional` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `global_charges`
--

INSERT INTO `global_charges` (`global_charges_id`, `charge_type`, `delivery_method`, `is_active`, `is_percentage`, `value`, `description`, `created_at`, `updated_at`, `is_optional`) VALUES
(1, 'delivery_fee', 'delivery', 1, 0, 1000.00, 'Egységesen 1000 Ft. kiszállítási díj.', '2025-08-16 14:02:25', '2025-08-16 14:15:54', 0),
(2, 'service_fee', 'dine-in', 1, 1, 10.00, 'Helyben fogyasztás esetén 10%-os szervízdíj.', '2025-08-16 14:03:28', '2025-08-16 14:03:28', 0),
(3, 'order_discount', 'delivery', 1, 1, 10.00, 'Egységesen 10% online kedvezmény.', '2025-08-16 14:04:30', '2025-08-16 14:04:30', 0),
(4, 'order_discount', 'dine-in', 1, 1, 10.00, 'Egységesen 10% online kedvezmény.', '2025-08-16 14:04:52', '2025-08-16 14:04:52', 0),
(5, 'order_discount', 'pickup', 1, 1, 10.00, 'Egységesen 10% online kedvezmény.', '2025-08-16 14:05:12', '2025-08-16 14:05:12', 0),
(6, 'cutlery', 'delivery', 1, 0, 200.00, 'Választható, evőeszköz 200 Ft.', '2025-08-16 14:06:46', '2025-08-17 15:27:48', 1),
(7, 'cutlery', 'dine-in', 1, 0, 200.00, 'Választható, evőeszköz 200 Ft.', '2025-08-16 14:07:10', '2025-09-13 07:03:37', 1),
(8, 'cutlery', 'pickup', 1, 0, 200.00, 'Választható, evőeszköz 200 Ft.', '2025-08-16 14:07:27', '2025-09-12 17:44:20', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2025_08_09_140237_create_users_table', 1),
(4, '2025_08_09_141021_create_dishes_table', 2),
(5, '2025_08_09_142436_create_tables_table', 3),
(6, '2025_08_09_142757_create_global_charges_table', 4),
(7, '2025_08_09_142944_create_feedback_table', 5),
(8, '2025_08_09_143652_create_orders_table', 6),
(9, '2025_08_09_175230_create_feedback_table', 7),
(10, '2025_08_09_175607_create_orders_table', 8),
(11, '2025_08_09_175931_create_order_items_table', 9),
(12, '2025_08_09_180153_create_bookings_table', 10),
(13, '2025_08_17_153319_add_cutlery_fee_to_orders_table', 11),
(14, '2025_08_17_164654_add_is_optional_to_global_charges_table', 12),
(15, '2025_08_17_165633_drop_is_optional_from_global_charges_table', 13),
(16, '2025_08_17_165815_add_is_optional_without_comment_to_global_charges_table', 13),
(17, '2025_08_17_212221_modify_delivery_method_column_in_orders_table', 14),
(18, '2025_08_22_143508_modify_bookings_table_for_multiple_tables', 15);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `orders`
--

CREATE TABLE `orders` (
  `orders_id` bigint(20) UNSIGNED NOT NULL,
  `users_id` bigint(20) UNSIGNED NOT NULL,
  `courier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('uj','keszul','atvetelre_kesz','atvetel_megtortent','kiszallitva','lezarva','torolve') NOT NULL DEFAULT 'uj',
  `payment_method` enum('bankkartya','keszpenz','szepkartya') NOT NULL,
  `delivery_method` varchar(20) NOT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT 0,
  `payment_time` timestamp NULL DEFAULT NULL,
  `delivery_fee` decimal(6,2) NOT NULL DEFAULT 0.00,
  `service_fee` decimal(6,2) NOT NULL DEFAULT 0.00,
  `cutlery_fee` decimal(6,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(6,2) NOT NULL DEFAULT 0.00,
  `subtotal_net` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal_sum` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_tax` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `rating_star` int(11) DEFAULT NULL,
  `rating_comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `orders`
--

INSERT INTO `orders` (`orders_id`, `users_id`, `courier_id`, `status`, `payment_method`, `delivery_method`, `is_paid`, `payment_time`, `delivery_fee`, `service_fee`, `cutlery_fee`, `discount`, `subtotal_net`, `subtotal_sum`, `total_tax`, `total_price`, `rating_star`, `rating_comment`, `created_at`, `updated_at`) VALUES
(8, 1, NULL, 'uj', 'szepkartya', 'delivery', 1, '2025-08-22 14:48:49', 0.00, 0.00, 0.00, 0.00, 3600.00, 3600.00, 0.00, 3600.00, NULL, NULL, '2025-08-17 20:16:21', '2025-08-22 14:48:49'),
(9, 1, NULL, 'uj', 'szepkartya', 'delivery', 1, '2025-08-22 11:29:15', 0.00, 0.00, 0.00, 0.00, 3750.00, 3750.00, 0.00, 3750.00, NULL, NULL, '2025-08-17 20:17:21', '2025-08-22 11:29:15'),
(10, 1, NULL, 'uj', 'bankkartya', 'delivery', 1, '2025-08-22 11:07:55', 0.00, 0.00, 300.00, 0.00, 7350.00, 7650.00, 0.00, 7650.00, NULL, NULL, '2025-08-18 05:00:19', '2025-08-22 11:07:55'),
(11, 1, NULL, 'torolve', 'bankkartya', 'delivery', 0, NULL, 0.00, 0.00, 0.00, 0.00, 5787.40, 7350.00, 1562.60, 7350.00, NULL, NULL, '2025-08-18 05:43:58', '2025-08-21 18:41:34'),
(12, 1, NULL, 'lezarva', 'bankkartya', 'dine-in', 0, NULL, 0.00, 0.00, 0.00, 0.00, 5787.40, 7350.00, 1562.60, 7350.00, NULL, NULL, '2025-08-18 06:26:19', '2025-08-21 18:43:58'),
(13, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 0.00, 0.00, 0.00, 0.00, 5787.40, 7350.00, 1562.60, 7350.00, NULL, NULL, '2025-08-18 06:51:46', '2025-08-21 18:38:40'),
(14, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 0.00, 0.00, 0.00, 0.00, 8740.16, 11100.00, 2359.84, 11100.00, NULL, NULL, '2025-08-18 07:08:46', '2025-08-21 18:38:33'),
(15, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 735.00, 5787.40, 7350.00, 1562.60, 7615.00, NULL, NULL, '2025-08-18 07:10:37', '2025-08-21 18:00:42'),
(16, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 750.00, 5905.51, 7500.00, 1594.49, 7750.00, NULL, NULL, '2025-08-18 07:11:34', '2025-08-21 17:49:03'),
(17, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 750.00, 5905.51, 7500.00, 1594.49, 7950.00, NULL, NULL, '2025-08-18 07:38:52', '2025-08-21 17:23:17'),
(18, 1, NULL, 'lezarva', 'bankkartya', 'dine-in', 0, NULL, 0.00, 966.00, 200.00, 966.00, 7606.30, 9660.00, 2053.70, 9860.00, NULL, NULL, '2025-08-21 10:11:44', '2025-08-21 17:23:06'),
(19, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 1254.00, 9874.02, 12540.00, 2665.98, 12486.00, NULL, NULL, '2025-08-21 10:30:40', '2025-08-21 18:27:59'),
(20, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 4440.00, NULL, NULL, '2025-08-21 10:34:17', '2025-08-21 18:27:55'),
(21, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 375.00, 2952.76, 3750.00, 797.24, 4575.00, NULL, NULL, '2025-08-21 10:39:15', '2025-08-21 18:27:43'),
(22, 1, 4, 'lezarva', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 894.00, 7039.37, 8940.00, 1900.63, 9246.00, NULL, NULL, '2025-08-21 10:41:07', '2025-08-21 18:27:38'),
(25, 1, NULL, 'keszul', 'szepkartya', 'delivery', 1, '2025-08-21 20:14:15', 1000.00, 0.00, 200.00, 1500.00, 11811.02, 15000.00, 3188.98, 14700.00, NULL, NULL, '2025-08-21 20:14:15', '2025-08-22 15:17:46'),
(26, 1, 4, 'lezarva', 'bankkartya', 'delivery', 1, '2025-08-21 20:18:49', 1000.00, 0.00, 200.00, 807.00, 6354.33, 8070.00, 1715.67, 8463.00, NULL, NULL, '2025-08-21 20:18:49', '2025-08-22 11:43:55'),
(27, 1, 4, 'lezarva', 'keszpenz', 'delivery', 1, '2025-08-22 11:29:57', 1000.00, 0.00, 0.00, 966.00, 7606.30, 9660.00, 2053.70, 9694.00, 5, 'Finomak voltak az ételek és az italok.', '2025-08-21 21:03:36', '2025-08-22 15:13:57'),
(28, 1, 4, 'lezarva', 'keszpenz', 'delivery', 1, '2025-08-22 10:49:01', 1000.00, 0.00, 200.00, 1254.00, 9874.02, 12540.00, 2665.98, 12486.00, 5, 'Nagyon finom volt minden étel.', '2025-08-21 21:19:28', '2025-08-22 15:13:01'),
(29, 1, NULL, 'torolve', 'bankkartya', 'dine-in', 0, NULL, 0.00, 1254.00, 200.00, 1254.00, 9874.02, 12540.00, 2665.98, 12740.00, NULL, NULL, '2025-08-22 13:42:14', '2025-08-22 14:46:36'),
(30, 1, NULL, 'atvetelre_kesz', 'keszpenz', 'dine-in', 1, '2025-08-24 08:49:04', 0.00, 966.00, 200.00, 966.00, 7606.30, 9660.00, 2053.70, 9860.00, NULL, NULL, '2025-08-24 08:45:53', '2025-08-26 12:26:07'),
(31, 1, 4, 'lezarva', 'bankkartya', 'delivery', 1, '2025-08-24 09:54:17', 1000.00, 0.00, 200.00, 225.00, 1771.65, 2250.00, 478.35, 3225.00, NULL, NULL, '2025-08-24 08:57:03', '2025-08-26 11:48:25'),
(32, 1, 4, 'lezarva', 'keszpenz', 'delivery', 1, '2025-08-24 09:48:57', 1000.00, 0.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 4440.00, NULL, NULL, '2025-08-24 09:01:33', '2025-08-26 12:30:20'),
(33, 1, 4, 'lezarva', 'bankkartya', 'delivery', 1, '2025-08-25 15:45:59', 1000.00, 0.00, 0.00, 385.00, 3031.50, 3850.00, 818.50, 4465.00, 5, 'Nagyon finomak voltak az ételek.', '2025-08-25 15:35:54', '2025-08-25 16:11:39'),
(34, 1, NULL, 'torolve', 'bankkartya', 'dine-in', 0, NULL, 0.00, 360.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 3800.00, NULL, NULL, '2025-08-25 15:49:27', '2025-08-26 12:41:56'),
(35, 1, NULL, 'atvetelre_kesz', 'keszpenz', 'dine-in', 1, '2025-08-26 12:32:02', 0.00, 375.00, 200.00, 375.00, 2952.76, 3750.00, 797.24, 3950.00, NULL, NULL, '2025-08-26 08:10:40', '2025-08-26 12:32:02'),
(36, 1, 4, 'kiszallitva', 'keszpenz', 'delivery', 1, '2025-08-26 12:31:06', 1000.00, 0.00, 0.00, 360.00, 2834.65, 3600.00, 765.35, 4240.00, NULL, NULL, '2025-08-26 10:50:27', '2025-08-27 06:36:05'),
(37, 1, NULL, 'torolve', 'bankkartya', 'dine-in', 0, NULL, 0.00, 966.00, 200.00, 966.00, 7606.30, 9660.00, 2053.70, 9860.00, NULL, NULL, '2025-08-26 15:25:42', '2025-08-26 18:11:40'),
(38, 1, 4, 'torolve', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 966.00, 7606.30, 9660.00, 2053.70, 9894.00, NULL, NULL, '2025-08-26 15:38:57', '2025-08-27 07:48:17'),
(39, 1, NULL, 'keszul', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 483.00, 3803.15, 4830.00, 1026.85, 5547.00, NULL, NULL, '2025-08-26 16:41:58', '2025-08-27 07:10:24'),
(40, 1, NULL, 'keszul', 'bankkartya', 'dine-in', 1, '2025-08-26 17:51:13', 0.00, 815.00, 200.00, 815.00, 6417.32, 8150.00, 1732.68, 8350.00, 5, 'Remélem, hogy minden étel finom és bőséges lesz.', '2025-08-26 16:58:46', '2025-08-27 07:07:33'),
(41, 1, NULL, 'atvetelre_kesz', 'keszpenz', 'dine-in', 1, '2025-08-27 09:29:45', 0.00, 360.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 3800.00, NULL, NULL, '2025-08-26 18:14:54', '2025-08-27 09:29:45'),
(42, 1, NULL, 'uj', 'keszpenz', 'delivery', 1, '2025-08-27 09:13:39', 1000.00, 0.00, 200.00, 520.00, 4094.49, 5200.00, 1105.51, 5880.00, NULL, NULL, '2025-08-27 09:08:37', '2025-08-27 09:13:39'),
(43, 1, NULL, 'uj', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 1280.00, 10078.74, 12800.00, 2721.26, 12520.00, NULL, NULL, '2025-08-27 19:18:18', '2025-08-27 19:18:18'),
(44, 1, NULL, 'uj', 'bankkartya', 'dine-in', 0, NULL, 0.00, 950.00, 200.00, 950.00, 7480.31, 9500.00, 2019.69, 9700.00, NULL, NULL, '2025-08-28 13:31:16', '2025-08-28 13:31:16'),
(45, 1, NULL, 'uj', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 4440.00, NULL, NULL, '2025-08-31 19:00:50', '2025-08-31 19:00:50'),
(46, 1, NULL, 'uj', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 360.00, 2834.65, 3600.00, 765.35, 4240.00, NULL, NULL, '2025-09-07 13:40:35', '2025-09-07 13:40:35'),
(47, 1, NULL, 'uj', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 360.00, 2834.65, 3600.00, 765.35, 4240.00, NULL, NULL, '2025-09-07 14:09:10', '2025-09-07 14:09:10'),
(48, 1, NULL, 'torolve', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 360.00, 2834.65, 3600.00, 765.35, 4240.00, NULL, NULL, '2025-09-07 14:09:58', '2025-09-07 14:45:10'),
(49, 1, NULL, 'torolve', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 0.00, 720.00, 5669.29, 7200.00, 1530.71, 7480.00, NULL, NULL, '2025-09-07 14:10:46', '2025-09-07 14:34:13'),
(50, 1, NULL, 'uj', 'bankkartya', 'dine-in', 0, NULL, 0.00, 884.00, 200.00, 884.00, 6960.63, 8840.00, 1879.37, 9040.00, NULL, NULL, '2025-09-08 17:01:15', '2025-09-08 17:01:15'),
(51, 1, 4, 'kiszallitva', 'bankkartya', 'delivery', 1, '2025-09-12 17:01:23', 1000.00, 0.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 4440.00, NULL, NULL, '2025-09-09 17:23:45', '2025-09-12 17:08:42'),
(52, 1, NULL, 'uj', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 1080.00, 8503.94, 10800.00, 2296.06, 10920.00, NULL, NULL, '2025-09-12 17:02:50', '2025-09-12 17:02:50'),
(53, 19, NULL, 'torolve', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 1200.00, 9448.82, 12000.00, 2551.18, 12000.00, NULL, NULL, '2025-09-12 21:11:35', '2025-09-12 21:12:14'),
(54, 19, 4, 'lezarva', 'szepkartya', 'delivery', 1, '2025-09-12 21:31:39', 1000.00, 0.00, 200.00, 1000.00, 7874.02, 10000.00, 2125.98, 10200.00, NULL, NULL, '2025-09-12 21:19:10', '2025-09-12 21:35:19'),
(55, 19, NULL, 'lezarva', 'bankkartya', 'pickup', 1, '2025-09-12 21:31:17', 0.00, 0.00, 200.00, 360.00, 2834.65, 3600.00, 765.35, 3440.00, NULL, NULL, '2025-09-12 21:19:32', '2025-09-12 21:35:11'),
(56, 19, NULL, 'lezarva', 'keszpenz', 'dine-in', 1, '2025-09-12 21:30:44', 0.00, 300.00, 200.00, 300.00, 2362.20, 3000.00, 637.80, 3200.00, NULL, NULL, '2025-09-12 21:19:58', '2025-09-12 21:34:58'),
(57, 1, NULL, 'uj', 'bankkartya', 'dine-in', 0, NULL, 0.00, 1284.00, 200.00, 1284.00, 10110.24, 12840.00, 2729.76, 13040.00, NULL, NULL, '2025-09-14 11:17:58', '2025-09-14 11:17:58'),
(58, 19, 21, 'kiszallitva', 'keszpenz', 'delivery', 1, '2025-09-14 14:08:05', 1000.00, 0.00, 200.00, 1832.00, 14425.20, 18320.00, 3894.80, 17688.00, 5, 'Nagyon finom volt minden étel és ital.', '2025-09-14 13:53:55', '2025-09-14 14:46:49'),
(59, 19, NULL, 'atvetelre_kesz', 'bankkartya', 'delivery', 0, NULL, 1000.00, 0.00, 200.00, 225.00, 1771.65, 2250.00, 478.35, 3225.00, NULL, NULL, '2025-09-14 14:52:49', '2025-09-14 14:53:36'),
(60, 19, NULL, 'atvetelre_kesz', 'bankkartya', 'dine-in', 1, '2025-09-14 18:08:16', 0.00, 365.00, 200.00, 365.00, 2874.02, 3650.00, 775.98, 3850.00, NULL, NULL, '2025-09-14 18:06:58', '2025-09-15 03:01:17'),
(61, 19, 21, 'lezarva', 'keszpenz', 'delivery', 1, '2025-09-15 02:07:05', 1000.00, 0.00, 200.00, 1440.00, 11338.58, 14400.00, 3061.42, 14160.00, NULL, NULL, '2025-09-15 01:59:43', '2025-09-15 03:01:10'),
(62, 19, 21, 'lezarva', 'bankkartya', 'delivery', 1, '2025-09-15 02:47:18', 1000.00, 0.00, 0.00, 337.50, 2657.48, 3375.00, 717.52, 4037.50, NULL, NULL, '2025-09-15 02:24:39', '2025-09-15 03:01:07');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `order_items`
--

CREATE TABLE `order_items` (
  `order_items_id` bigint(20) UNSIGNED NOT NULL,
  `orders_id` bigint(20) UNSIGNED NOT NULL,
  `dishes_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(50) NOT NULL DEFAULT 'normal',
  `size_multiplier` decimal(4,2) NOT NULL DEFAULT 1.00,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `extra_ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extra_ingredients`)),
  `excluded_ingredients` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`excluded_ingredients`)),
  `ingredient_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `exclusion_discount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `final_unit_price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(8,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `order_items`
--

INSERT INTO `order_items` (`order_items_id`, `orders_id`, `dishes_id`, `size`, `size_multiplier`, `quantity`, `extra_ingredients`, `excluded_ingredients`, `ingredient_price`, `exclusion_discount`, `final_unit_price`, `tax_amount`, `subtotal`, `created_at`, `updated_at`) VALUES
(4, 8, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 4572.00, '2025-08-17 20:16:21', '2025-08-17 20:16:21'),
(5, 9, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 0.00, 0.00, 3750.00, 1012.50, 4762.50, '2025-08-17 20:17:21', '2025-08-17 20:17:21'),
(6, 10, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 4572.00, '2025-08-18 05:00:19', '2025-08-18 05:00:19'),
(7, 10, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 0.00, 0.00, 3750.00, 1012.50, 4762.50, '2025-08-18 05:00:19', '2025-08-18 05:00:19'),
(8, 11, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 4572.00, '2025-08-18 05:43:58', '2025-08-18 05:43:58'),
(9, 11, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 0.00, 0.00, 3750.00, 1012.50, 4762.50, '2025-08-18 05:43:58', '2025-08-18 05:43:58'),
(10, 12, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 4572.00, '2025-08-18 06:26:19', '2025-08-18 06:26:19'),
(11, 12, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 0.00, 0.00, 3750.00, 1012.50, 4762.50, '2025-08-18 06:26:19', '2025-08-18 06:26:19'),
(12, 13, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-18 06:51:46', '2025-08-18 06:51:46'),
(13, 13, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 3750.00, '2025-08-18 06:51:46', '2025-08-18 06:51:46'),
(14, 14, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-18 07:08:46', '2025-08-18 07:08:46'),
(15, 14, 1, 'Normál', 1.00, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 7500.00, '2025-08-18 07:08:46', '2025-08-18 07:08:46'),
(17, 15, 1, 'Normál', 1.00, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 7500.00, '2025-08-18 07:10:37', '2025-08-21 13:25:15'),
(18, 16, 1, 'Normál', 1.00, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 7500.00, '2025-08-18 07:11:34', '2025-08-18 07:11:34'),
(19, 17, 1, 'Normál', 1.00, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 7500.00, '2025-08-18 07:38:52', '2025-08-18 07:38:52'),
(21, 18, 1, 'Kicsi', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 3030.00, '2025-08-21 10:11:44', '2025-08-21 13:23:13'),
(23, 19, 1, 'Nagy', 1.20, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 5364.00, 1448.28, 10728.00, '2025-08-21 10:30:40', '2025-08-21 10:30:40'),
(24, 20, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-21 10:34:17', '2025-08-21 10:34:17'),
(25, 21, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 3750.00, '2025-08-21 10:39:15', '2025-08-21 13:07:55'),
(26, 22, 1, 'Nagy', 1.20, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 5364.00, 1448.28, 5364.00, '2025-08-21 10:41:07', '2025-08-21 13:25:41'),
(31, 21, 1, 'Kicsi', 0.80, 2, NULL, NULL, 0.00, 0.00, 2880.00, 777.60, 5760.00, '2025-08-21 13:25:58', '2025-08-21 13:25:58'),
(32, 25, 1, 'Normál', 1.00, 4, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 15000.00, '2025-08-21 20:14:15', '2025-08-21 20:14:15'),
(33, 26, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-21 20:18:49', '2025-08-21 20:18:49'),
(34, 26, 1, 'Nagy', 1.20, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 4470.00, 1206.90, 4470.00, '2025-08-21 20:18:49', '2025-08-21 20:18:49'),
(35, 27, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-21 21:03:36', '2025-08-21 21:03:36'),
(36, 27, 1, 'Kicsi', 0.80, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 6060.00, '2025-08-21 21:03:36', '2025-08-21 21:03:36'),
(37, 28, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-21 21:19:28', '2025-08-21 21:19:28'),
(38, 28, 1, 'Nagy', 1.20, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 4470.00, 1206.90, 8940.00, '2025-08-21 21:19:28', '2025-08-21 21:19:28'),
(39, 29, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-22 13:42:14', '2025-08-22 13:42:14'),
(40, 29, 1, 'Nagy', 1.20, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 4470.00, 1206.90, 8940.00, '2025-08-22 13:42:14', '2025-08-22 13:42:14'),
(41, 30, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-24 08:45:53', '2025-08-24 08:45:53'),
(42, 30, 1, 'Kicsi', 0.80, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 6060.00, '2025-08-24 08:45:53', '2025-08-24 08:45:53'),
(43, 31, 2, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 2250.00, '2025-08-24 08:57:03', '2025-08-24 08:57:03'),
(44, 32, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-24 09:01:33', '2025-08-24 09:01:33'),
(45, 33, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[]', 250.00, 0.00, 3850.00, 1039.50, 3850.00, '2025-08-25 15:35:54', '2025-08-25 15:35:54'),
(46, 34, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-25 15:49:27', '2025-08-25 15:49:27'),
(47, 35, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3750.00, 1012.50, 3750.00, '2025-08-26 08:10:40', '2025-08-26 08:10:40'),
(48, 36, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-26 10:50:27', '2025-08-26 10:50:27'),
(49, 37, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-26 15:25:42', '2025-08-26 15:25:42'),
(50, 37, 1, 'Kicsi', 0.80, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 6060.00, '2025-08-26 15:25:42', '2025-08-26 15:25:42'),
(51, 38, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-26 15:38:57', '2025-08-26 15:38:57'),
(52, 38, 1, 'Kicsi', 0.80, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 6060.00, '2025-08-26 15:38:57', '2025-08-26 15:38:57'),
(53, 39, 24, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 1800.00, 486.00, 1800.00, '2025-08-26 16:41:58', '2025-08-26 16:41:58'),
(54, 39, 1, 'Kicsi', 0.80, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"csirke\"]', 250.00, -100.00, 3030.00, 818.10, 3030.00, '2025-08-26 16:41:58', '2025-08-26 16:41:58'),
(55, 40, 2, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 2250.00, '2025-08-26 16:58:46', '2025-08-26 16:58:46'),
(56, 40, 2, 'Nagy', 1.20, 2, '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', '[\"lime\",\"chili\"]', 350.00, -100.00, 2950.00, 796.50, 5900.00, '2025-08-26 16:58:46', '2025-08-26 16:58:46'),
(57, 41, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-26 18:14:54', '2025-08-26 18:14:54'),
(58, 42, 2, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 2250.00, '2025-08-27 09:08:37', '2025-08-27 09:08:37'),
(59, 42, 2, 'Nagy', 1.20, 1, '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', '[\"lime\",\"chili\"]', 350.00, -100.00, 2950.00, 796.50, 2950.00, '2025-08-27 09:08:37', '2025-08-27 09:08:37'),
(60, 43, 21, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 1000.00, 270.00, 1000.00, '2025-08-27 19:18:18', '2025-08-27 19:18:18'),
(61, 43, 2, 'Nagy', 1.20, 4, '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', '[\"lime\",\"chili\"]', 350.00, -100.00, 2950.00, 796.50, 11800.00, '2025-08-27 19:18:18', '2025-08-27 19:18:18'),
(62, 44, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-28 13:31:16', '2025-08-28 13:31:16'),
(63, 44, 2, 'Nagy', 1.20, 2, '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', '[\"lime\",\"chili\"]', 350.00, -100.00, 2950.00, 796.50, 5900.00, '2025-08-28 13:31:16', '2025-08-28 13:31:16'),
(64, 45, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-08-31 19:00:50', '2025-08-31 19:00:50'),
(65, 46, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-07 13:40:35', '2025-09-07 13:40:35'),
(66, 47, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-07 14:09:10', '2025-09-07 14:09:10'),
(67, 48, 1, 'Normál', 1.00, 1, '[]', '[]', 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-07 14:09:58', '2025-09-07 14:09:58'),
(68, 49, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-07 14:10:46', '2025-09-07 14:10:46'),
(69, 49, 1, 'Normál', 1.00, 1, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"rukkola sal\\u00e1ta\",\"parmez\\u00e1n sajt\"]', 300.00, -300.00, 3600.00, 972.00, 3600.00, '2025-09-07 14:10:46', '2025-09-07 14:10:46'),
(70, 50, 3, 'Nagy', 1.20, 2, '[\"extra rizs\",\"koriander\"]', '[\"vaj\",\"curry\"]', 250.00, -150.00, 4420.00, 1193.40, 8840.00, '2025-09-08 17:01:15', '2025-09-08 17:01:15'),
(71, 51, 1, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-09 17:23:45', '2025-09-09 17:23:45'),
(72, 52, 1, 'Normál', 1.00, 3, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 10800.00, '2025-09-12 17:02:50', '2025-09-12 17:02:50'),
(73, 53, 2, 'Normál', 1.00, 2, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 4500.00, '2025-09-12 21:11:35', '2025-09-12 21:11:35'),
(74, 53, 2, 'Normál', 1.00, 3, '[\"toj\\u00e1s\",\"parmez\\u00e1n\"]', '[\"lime\",\"chili\"]', 350.00, -100.00, 2500.00, 675.00, 7500.00, '2025-09-12 21:11:35', '2025-09-12 21:11:35'),
(75, 54, 5, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2700.00, 729.00, 2700.00, '2025-09-12 21:19:10', '2025-09-12 21:19:10'),
(76, 54, 4, 'Nagy', 1.20, 2, '[\"toj\\u00e1s\"]', '[\"sz\\u00f3jasz\\u00f3sz\"]', 150.00, -100.00, 3650.00, 985.50, 7300.00, '2025-09-12 21:19:10', '2025-09-12 21:19:10'),
(77, 55, 3, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3600.00, 972.00, 3600.00, '2025-09-12 21:19:32', '2025-09-12 21:19:32'),
(78, 56, 8, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 3000.00, 810.00, 3000.00, '2025-09-12 21:19:58', '2025-09-12 21:19:58'),
(79, 57, 1, 'Nagy', 1.20, 2, '[\"pir\\u00edtott keny\\u00e9rkocka\",\"extra \\u00f6ntet\"]', '[\"rukkola sal\\u00e1ta\",\"parmez\\u00e1n sajt\"]', 300.00, -300.00, 4320.00, 1166.40, 8640.00, '2025-09-14 11:17:58', '2025-09-14 11:17:58'),
(80, 57, 2, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 2250.00, '2025-09-14 11:17:58', '2025-09-14 11:17:58'),
(81, 57, 2, 'Kicsi', 0.80, 1, '[\"toj\\u00e1s\"]', '[\"lime\"]', 200.00, -50.00, 1950.00, 526.50, 1950.00, '2025-09-14 11:17:58', '2025-09-14 11:17:58'),
(82, 58, 5, 'Nagy', 1.20, 2, '[\"sajt\",\"bacon\"]', '[]', 350.00, 0.00, 3590.00, 969.30, 7180.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(83, 58, 12, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2500.00, 675.00, 2500.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(84, 58, 11, 'Nagy', 1.20, 1, '[\"ban\\u00e1n\",\"kiwi\"]', '[\"citrom\"]', 300.00, -100.00, 2360.00, 637.20, 2360.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(85, 58, 18, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2500.00, 675.00, 2500.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(86, 58, 16, 'Nagy', 1.20, 1, '[\"lime\",\"narancs\"]', '[]', 300.00, 0.00, 1380.00, 372.60, 1380.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(87, 58, 19, 'Normál', 1.00, 2, '[\"n\\u00e1dcukor\",\"extra tejsz\\u00ednhab\"]', '[]', 300.00, 0.00, 1200.00, 324.00, 2400.00, '2025-09-14 13:53:55', '2025-09-14 13:53:55'),
(88, 59, 2, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2250.00, 607.50, 2250.00, '2025-09-14 14:52:49', '2025-09-14 14:52:49'),
(89, 60, 21, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 1000.00, 270.00, 1000.00, '2025-09-14 18:06:58', '2025-09-14 18:06:58'),
(90, 60, 3, 'Normál', 1.00, 1, '[]', '[\"curry\"]', 0.00, -50.00, 2650.00, 715.50, 2650.00, '2025-09-14 18:06:58', '2025-09-14 18:06:58'),
(91, 61, 8, 'Nagy', 1.20, 2, '[\"parmez\\u00e1n sajt\",\"rukkola\"]', '[\"bazsalikom\"]', 300.00, -200.00, 3700.00, 999.00, 7400.00, '2025-09-15 01:59:43', '2025-09-15 01:59:43'),
(92, 61, 12, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2500.00, 675.00, 2500.00, '2025-09-15 01:59:43', '2025-09-15 01:59:43'),
(93, 61, 18, 'Normál', 1.00, 1, '[\"csoki\\u00f6ntet\",\"extra tejsz\\u00ednhab\"]', '[\"eper\\u00f6ntet\"]', 300.00, -100.00, 2700.00, 729.00, 2700.00, '2025-09-15 01:59:43', '2025-09-15 01:59:43'),
(94, 61, 23, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 1125.00, 303.75, 1125.00, '2025-09-15 01:59:43', '2025-09-15 01:59:43'),
(95, 61, 22, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 675.00, 182.25, 675.00, '2025-09-15 01:59:43', '2025-09-15 01:59:43'),
(96, 62, 22, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 675.00, 182.25, 675.00, '2025-09-15 02:24:39', '2025-09-15 02:24:39'),
(97, 62, 5, 'Normál', 1.00, 1, NULL, NULL, 0.00, 0.00, 2700.00, 729.00, 2700.00, '2025-09-15 02:24:39', '2025-09-15 02:24:39');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `tables`
--

CREATE TABLE `tables` (
  `tables_id` bigint(20) UNSIGNED NOT NULL,
  `table_code` varchar(10) NOT NULL,
  `location` varchar(50) NOT NULL DEFAULT 'beltér',
  `position` varchar(50) NOT NULL DEFAULT 'közép',
  `capacity` int(11) NOT NULL,
  `is_reservable` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `tables`
--

INSERT INTO `tables` (`tables_id`, `table_code`, `location`, `position`, `capacity`, `is_reservable`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'bj1', 'beltér', 'jobb', 4, 1, 'Ablak mellett', '2025-08-22 12:58:57', '2025-08-22 12:58:57'),
(2, 'tk2', 'terasz', 'közép', 6, 1, 'Napernyő alatt', '2025-08-22 12:58:57', '2025-08-22 12:58:57'),
(3, 'bb3', 'beltér', 'bal', 2, 1, 'Ablak mellett', '2025-08-22 12:58:57', '2025-08-22 13:24:45'),
(4, 'tb4', 'terasz', 'bal', 4, 1, 'Közel a bejárathoz', '2025-08-22 12:58:57', '2025-08-22 12:58:57'),
(5, 'bk5', 'beltér', 'közép', 8, 1, 'Nagyobb társaságoknak', '2025-08-22 12:58:57', '2025-09-14 11:35:24'),
(6, 'bb6', 'beltér', 'bal', 8, 1, 'Családi asztal 4 db gyerek székkel', '2025-08-22 13:14:53', '2025-08-22 13:15:29');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `users_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `postal_code` varchar(10) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `street_name` varchar(100) DEFAULT NULL,
  `street_number` varchar(10) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `password_hint` varchar(255) DEFAULT NULL,
  `role` enum('user','courier','admin') NOT NULL DEFAULT 'user',
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`users_id`, `name`, `email`, `phone`, `postal_code`, `city`, `street_name`, `street_number`, `password`, `password_hint`, `role`, `active`, `must_change_password`, `remember_token`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Teszt User', 'tesztuser@example.hu', '+36-30-123-4567', '3000', 'Tesztváros', 'Teszt utca', '1', '$2y$12$GQrxCwkVN7CGGnxz2cTycuzzKn7SDg/7Si5NSj.LlVC7HHy2n50fa', NULL, 'user', 1, 0, 'RI8FSrM4GKHvo3kolhZLsGMUVpN21rEfQ7gHEG7xAnt1QHFmlfo04FhlwAxz', '2025-09-14 16:09:01', '2025-08-10 13:01:34', '2025-09-14 16:09:01'),
(2, 'Teszt Admin', 'tesztadmin@example.hu', '0630123456', '1100', 'Tesztadminváros', 'Tesztadmin utca', '11.', '$2y$12$sirLc4sd3uIb1evloRI97u6aMx1QV5gPwJ2tXAABEMiR.22aRrkU2', NULL, 'admin', 1, 0, 'EB5ZAQPmlmzgbFaZwdsA0FMUBZnYCNTjuAmS046Y6FAJtiJmPG3LWY1VdwFk', '2025-09-15 03:22:42', '2025-08-10 15:09:46', '2025-09-15 03:22:42'),
(3, 'Pusztai Sándor', 'pusztai.sandor@example.hu', '06301234567', '1234', 'Budapest', 'Balázs utca', '1.', '$2y$12$wQoMnLNig92LFLPSn1p/D.eFKvETVBpg6Xd70O.fO1a/Pp.sRIZiG', NULL, 'courier', 0, 0, NULL, '2025-08-12 18:10:39', '2025-08-10 16:04:25', '2025-09-14 14:54:29'),
(4, 'Teszt Courier', 'tesztcourier@example.hu', '06301234567', '1200', 'Tesztcourierváros', 'Tesztcourier utca', '12.', '$2y$12$pUk4eBQ2Cc1YUUcE5wKe5.cTd/Qe6WHJ68Sp336XaofZNiwVYc0GS', NULL, 'courier', 1, 0, 'GW1zsyR1OvgAJpManZJFPM1o6CFWH36DS2HWiSBpHYfmzQXWHYAKzGKVN5bP', '2025-09-14 18:11:29', '2025-08-10 16:12:08', '2025-09-14 18:11:29'),
(5, 'Teszt Sándor', 'tesztsandor@example.hu', '0612345678', '1300', 'Tesztsandorvaros', 'Tesztsandor utca', '13', '$2y$12$93/mZsLJnXD53fbwXr.nkuxqtWrGBIs/snh66gfHcDZpNxJEcWmmC', NULL, 'user', 1, 0, NULL, NULL, '2025-08-11 16:21:59', '2025-08-11 16:21:59'),
(6, 'Teszt Renáta', 'tesztrenata@example.hu', '06201234567', '1400', 'Tesztrenatavaros', 'Tesztrenata utca', '14.', '$2y$12$erUnsZIf5LkLpv7TSzPiIOV8FWf0fcj3j5UfJ7pfeqsAqd.Ult5dW', NULL, 'user', 1, 0, 'MzH3QkL8GOzxJPg9NaEfAFtlTBUS6fRJ3MEVeyDtFtjMnzPbyfTlsoujyJkF', '2025-09-14 16:00:24', '2025-08-11 17:22:30', '2025-09-14 16:00:24'),
(7, 'Pusztai Roland Sándor', 'pusztai.roland.sandor@example.hu', NULL, '2000', 'Tesztváros', 'Teszt utca', '1', '$2y$12$e/WP0hARsdz7OLpidSANs.8wne0CdVA9HYK4BYa.x6JjB/JwF50Mq', '$2y$12$HrI4tcnBSNoFxTd0YhxYi.7lkrx5qiTBfkd5HFzS92YVEgTikRzrG', 'courier', 0, 0, 'xYpQBqfaGh20CncfqydQc01Z9pDUWNLT89TCn2vnbV7XO40Uht28Uzm7EZWQ', '2025-09-12 16:53:44', '2025-08-12 17:48:15', '2025-09-14 14:54:22'),
(8, 'Teszt János', 'teszt.janos.7167@esszencia.local', NULL, NULL, NULL, NULL, NULL, '$2y$12$UFPI7IzZwSMdTz.KzLXvtOyiln2ugN/CnuOn/8RZdqN/KKN2duPxu', '$2y$12$b3DMowZ7pAOOvMpp0w5hTOfJXgs/D1sJK0gm7w1FOskZks3mjWEe.', 'courier', 1, 1, 'TeybTaLgZvYCXLlCeagPbmpm0a7PlfoOrEtZlEuASUvbpZpkHP0HhLJYBil7', '2025-08-12 20:03:53', '2025-08-12 20:02:10', '2025-08-27 11:33:15'),
(9, 'Teszt Ferenc', 'teszt.ferenc@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$2DD.PuCugqMfyNrlcn7KjemAwO2YiD8O6V60SgguUd/5FmBGhNkt6', '$2y$12$h4751wuQN9zt1t.Qr41YduAb4DQQBJwCB.5CaqGP55Od0t1Lug2gG', 'courier', 1, 0, 'EDL0WWs1Qm0ZS9hNaD6ZKISVXDYcf9N4phlpHCeSpz3Ujybf7CMg5EA1nFlG', '2025-09-14 18:18:50', '2025-08-12 20:15:04', '2025-09-14 18:19:29'),
(10, 'Teszt Tamás', 'teszt.tamas.1494@esszencia.local', NULL, NULL, NULL, NULL, NULL, '$2y$12$QpC/ybUzBIJErp8.E1vAB.YYrrmbqxR80cic9eVIFs8jv.kQe1LMm', '$2y$12$aE1/1Z9mTZlBVME3A5rY2OWrJBq6TiMsq1ckK3.c1WLF0TT3QYGaK', 'courier', 1, 1, NULL, NULL, '2025-08-12 21:14:04', '2025-08-27 11:42:59'),
(11, 'Teszt Anna', 'tesztanna@example.hu', '06301234567', '2000', 'Tesztváros', 'Teszt utca', '1.', '$2y$12$.Ps.0iZ3MXNB5PLLNhu3TuYgBJdSE.S9MZqhcAfweschCPZNE7sc6', '$2y$12$rJaXyt.bQLlgQnbxnhTcgOzE2N5ftMT2XjaPPmQfJFdTAWgp.empG', 'courier', 0, 0, NULL, '2025-08-13 17:31:30', '2025-08-13 17:30:26', '2025-09-14 14:54:43'),
(12, 'Teszt Tünde', 'teszttunde@example.hu', '+36-20-123-4567', '2345', 'Teszt-Tundevaros', 'Teszttunde utca', '23', '$2y$12$lTsNqoy9P66t/K7MOd1ZgOyZI7uGv60Ovy0gsd2cR36JTu1.22.Mu', NULL, 'admin', 1, 0, 'JRb7FryV2gnXsStf67W0l6EygQkCVYfsAQsr7eo8b8azzlD0vjbY7LsWLclH', '2025-08-24 16:15:45', '2025-08-24 15:36:13', '2025-08-24 16:16:03'),
(13, 'Teszt Klaudia', 'tesztklaudia@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$q/drvfRMhJ7wNNZY1L9lsuzSqbFgr0ogSWjnShKMdFwEAo2d1Tv1.', '$2y$12$AqTfjmmw0OqQPo725k3f9eeR3pvg.N2l48BHpUDf.QjgxWIG3Vrzi', 'courier', 0, 0, 'LliPXDAvGhSme6MQHzBhRWbqh0rvum6U3KRX55mNGoThDZ3MPvGri0zntGL4', '2025-08-24 17:36:09', '2025-08-24 17:12:05', '2025-09-14 14:54:55'),
(14, 'Teszt Réka', 'tesztreka@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$sHY4afqQA2NnYpMWWQf4POhN5f.Bmq8UK7rKohc58OHM8YqelXNWm', '$2y$12$8k.wvuBAXWNcGta20Sy5au7ZihlDauEjOxMnAxYFWoKeiM7HlSwKO', 'courier', 0, 0, 'wvyCB55on0Juam4prw0L6x5VFIuFwuaXoKJAnNs15zn3BKGVzSPBHUzjpHF7', '2025-08-24 20:42:20', '2025-08-24 17:34:03', '2025-09-14 14:55:03'),
(15, 'Teszt Zoltán', 'teszt.zoltan.9403@esszencia.local', NULL, NULL, NULL, NULL, NULL, '$2y$12$83MwUpcxfUoRzCeAnLCOpuIAxeaKQ9C8WRNUTp6l55u7AxWdMhePG', '$2y$12$tEFO6LwANGWWMAE4zDg0KO8UeSrCZnRWz2g6F6YqH/5TNvYmxAiTK', 'courier', 1, 1, NULL, NULL, '2025-08-25 16:01:17', '2025-08-27 11:32:36'),
(16, 'Kiss-Kovács János', 'kisskovacsjanos@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$9EoT5O91Gs6wWLJgnn0FCeW/.sFWAeSSsDj98LGu9EWPuAkpG35NO', NULL, 'user', 1, 0, '0jo08CFFnlfkguocguJl29FgCuTPc2S7P4CvQ7YUD9DCo4IEWkfx2FOP5CfR', '2025-09-14 16:04:38', '2025-08-27 10:04:59', '2025-09-14 16:04:38'),
(17, 'Szűcs Réka', 'szucs.reka@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$N0aSoU4aJHOpDN9YJmWX0eXrqfZL4YRs82OMK5HD7F3czpL/vLyFm', '$2y$12$j9ZjerSi9p6lmTIIDkmAM.as9j8G.Cbgiy9oFTHk0e7S7jZpgYL2.', 'courier', 0, 0, 'sadS5c85Pliz9TqCUB0LEQY0IHnfBw2Orll1vsdk1Yh1dU1aAgAmFEciEv0Y', '2025-08-27 12:12:10', '2025-08-27 11:18:21', '2025-09-14 14:54:35'),
(18, 'Kiss-Kovács János', 'kiss.kovacs.janos@example.hu', NULL, NULL, NULL, NULL, NULL, '$2y$12$o8zoIFfIuQpaBaXQk6p0WeWcUs1I4jhgsLH7/IkQxIEjHfM1dTPBy', '$2y$12$GlT9hx3VUbGGsxq9alifPuKvCA/9cA8DBAwdpkGBO4Zjl6MbPyUQW', 'admin', 0, 0, 'XbjplPkq1AIhlo6LOtSeTasHloNyZxeAn9FLAw525lnrFYwhUwi620jN78Io', '2025-09-12 17:53:16', '2025-09-12 17:50:49', '2025-09-14 14:54:10'),
(19, 'Májkell Dzsekszon', 'majkell.dzsekszon@example.hu', '+36-20-123-4567', '2000', 'Loszandzselesz', 'Kiss út', '20', '$2y$12$l0TZPkXnKh8v/qh0KiZy3.1HaZAB.BvcNqKddpUkOOvzIH0oXMHy.', NULL, 'user', 1, 0, 's2skR9d8ynd1MSu2uoh6FbIXkUSL2cJtGOeM6IWrH0pjRJcQPH2sSb8KCGl4', '2025-09-15 03:32:53', '2025-09-12 21:03:02', '2025-09-15 03:32:53'),
(20, 'Mikell Andzselo', 'mikell.andzselo@example.hu', '+36-40-123-4567', '4000', 'Adminváros', 'Admin utca', '30', '$2y$12$733NDy07RKUra9JpARwDkOPw3FMC7wfkz.GfxAWcLJaGbIqKXbUz2', '$2y$12$aEhSuVQOJdRssEJbdz4qDORiGgCaMnATVLHHYiwWJbyQeoBkwqpsC', 'admin', 1, 0, 'xwglZMCJPxBCSQpZUYapLd9nRQ0tD4ofPr0MBiNmji4DFe7AGy5hfElel0Dc', '2025-09-15 02:59:36', '2025-09-14 13:33:55', '2025-09-15 02:59:36'),
(21, 'Keni Rodzsersz', 'keni.rodzsersz@example.hu', '+36-30-123-4567', '3000', 'Futárváros', 'Futár utca', '20', '$2y$12$P9eb7tnPHOpb6imJ7JG.quhhNUxn7kA9ydbsxtJ0gkyHJB5TCae.i', '$2y$12$9AxOh7huacehywnS512C4.csF77T4cLtqvFMA7C7IL2B2sN1mIO6S', 'courier', 1, 0, 'fWsatXyb8Udf7uXyUBvFBK7rgUFP3b3gJyRX4wIgEY1FYNctSNMW6AcnifW3', '2025-09-15 02:48:08', '2025-09-14 13:35:30', '2025-09-15 02:48:08'),
(22, 'Wincs Eszter', 'wincs.eszter@example.hu', '+36-40-123-4567', '4000', 'Debrecen', 'Lőpor utca', '40', '$2y$12$I9/zGkGX4EPFfrv8Br/ZCeWpC.7GM5uolZRMaTLLmmHtWmeAG3bqq', NULL, 'user', 1, 0, NULL, '2025-09-15 03:25:10', '2025-09-15 03:25:10', '2025-09-15 03:25:10');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`bookings_id`),
  ADD KEY `bookings_users_id_foreign` (`users_id`),
  ADD KEY `bookings_orders_id_foreign` (`orders_id`);

--
-- A tábla indexei `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `dishes`
--
ALTER TABLE `dishes`
  ADD PRIMARY KEY (`dishes_id`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `feedback_users_id_foreign` (`users_id`);

--
-- A tábla indexei `global_charges`
--
ALTER TABLE `global_charges`
  ADD PRIMARY KEY (`global_charges_id`);

--
-- A tábla indexei `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- A tábla indexei `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`orders_id`),
  ADD KEY `orders_users_id_foreign` (`users_id`),
  ADD KEY `orders_courier_id_foreign` (`courier_id`);

--
-- A tábla indexei `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `order_items_orders_id_foreign` (`orders_id`),
  ADD KEY `order_items_dishes_id_foreign` (`dishes_id`);

--
-- A tábla indexei `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`tables_id`),
  ADD UNIQUE KEY `tables_table_code_unique` (`table_code`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `bookings`
--
ALTER TABLE `bookings`
  MODIFY `bookings_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `dishes`
--
ALTER TABLE `dishes`
  MODIFY `dishes_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT a táblához `global_charges`
--
ALTER TABLE `global_charges`
  MODIFY `global_charges_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT a táblához `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT a táblához `orders`
--
ALTER TABLE `orders`
  MODIFY `orders_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT a táblához `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_items_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT a táblához `tables`
--
ALTER TABLE `tables`
  MODIFY `tables_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `users_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_orders_id_foreign` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`orders_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`);

--
-- Megkötések a táblához `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_courier_id_foreign` FOREIGN KEY (`courier_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `orders_users_id_foreign` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`);

--
-- Megkötések a táblához `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_dishes_id_foreign` FOREIGN KEY (`dishes_id`) REFERENCES `dishes` (`dishes_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_orders_id_foreign` FOREIGN KEY (`orders_id`) REFERENCES `orders` (`orders_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
