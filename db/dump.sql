SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `orders` (
                          `id` int(11) NOT NULL,
                          `user_id` int(11) NOT NULL,
                          `total_price` smallint(6) DEFAULT NULL,
                          `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`billing_address`)),
                          `creation_date` datetime NOT NULL,
                          `accepted` tinyint(1) DEFAULT NULL,
                          `removed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

CREATE TABLE `orders_items` (
                                `order_id` int(11) NOT NULL,
                                `id` int(11) NOT NULL,
                                `description` text COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `orders_items`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `orders_items`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

