-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 04, 2026 at 02:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techstore_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `type`, `created_at`) VALUES
(1, 1, 'Logout', 'LOGOUT', '2026-04-03 15:29:04'),
(2, 0, 'New user registered: smoshiee34@gmail.com', 'REGISTRATION', '2026-04-04 08:17:43'),
(3, 0, 'New user registered: smoshiee304@gmail.com', 'REGISTRATION', '2026-04-04 08:18:18'),
(4, 2, 'Login: serjohn', 'LOGIN', '2026-04-04 08:19:41'),
(5, 2, 'Logout', 'LOGOUT', '2026-04-04 08:24:55'),
(6, 2, 'Login: serjohn', 'LOGIN', '2026-04-04 08:25:01'),
(7, 2, 'Logout', 'LOGOUT', '2026-04-04 08:28:26'),
(8, 1, 'Login: john', 'LOGIN', '2026-04-04 08:29:27'),
(9, 1, 'Added product: juice', 'PRODUCT', '2026-04-04 08:43:20'),
(10, 1, 'Updated product: juice', 'PRODUCT', '2026-04-04 08:49:52'),
(11, 1, 'Updated product: juice', 'PRODUCT', '2026-04-04 08:50:30'),
(12, 1, 'Added product: aswang', 'PRODUCT', '2026-04-04 09:06:17'),
(13, 1, 'Sale completed: INV-20260404091547456', 'SALE', '2026-04-04 09:15:47'),
(14, 1, 'Sale completed: INV-20260404092817786', 'SALE', '2026-04-04 09:28:18'),
(15, 1, 'Added product: shabu', 'PRODUCT', '2026-04-04 09:46:41'),
(16, 1, 'Deleted product: shabu', 'PRODUCT', '2026-04-04 09:56:43'),
(17, 1, 'Added stock to aswang: +322', 'STOCK', '2026-04-04 10:15:15'),
(18, 1, 'Logout', 'LOGOUT', '2026-04-04 10:30:04'),
(19, 0, 'New user registered: goku@gmail.com', 'REGISTRATION', '2026-04-04 10:30:49'),
(20, 3, 'Login: goku', 'LOGIN', '2026-04-04 10:31:06'),
(21, 3, 'Logout', 'LOGOUT', '2026-04-04 10:31:47'),
(22, 1, 'Login: john', 'LOGIN', '2026-04-04 10:31:57'),
(23, 1, 'Deleted user ID: 3', 'USER', '2026-04-04 10:32:56'),
(24, 1, 'Updated user ID: 2', 'USER', '2026-04-04 10:38:41'),
(25, 1, 'Sale completed: INV-20260404104027941', 'SALE', '2026-04-04 10:40:27'),
(26, 1, 'Logout', 'LOGOUT', '2026-04-04 10:54:21'),
(27, 1, 'Login: john', 'LOGIN', '2026-04-04 10:54:47'),
(28, 1, 'Added product: agogo', 'PRODUCT', '2026-04-04 11:00:53'),
(29, 1, 'Logout', 'LOGOUT', '2026-04-04 11:05:03'),
(30, 1, 'Login: john', 'LOGIN', '2026-04-04 11:19:05'),
(31, 1, 'Updated product: juice', 'PRODUCT', '2026-04-04 11:45:44'),
(32, 1, 'Updated product: juice', 'PRODUCT', '2026-04-04 11:46:08'),
(33, 1, 'Updated product: aswang', 'PRODUCT', '2026-04-04 11:51:51'),
(34, 1, 'Added stock to aswang: +123', 'STOCK', '2026-04-04 11:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Uncategorized', 'Default category', '2026-04-04 01:43:11', NULL),
(3, 'Electronics', 'Gadgets, devices, accessories', '2026-04-04 02:44:36', NULL),
(4, 'Clothing & Apparel', 'Shirts, pants, dresses, uniforms', '2026-04-04 02:44:36', NULL),
(5, 'Food & Beverages', 'Grocery items, drinks, snacks', '2026-04-04 02:44:36', NULL),
(6, 'Health & Beauty', 'Cosmetics, supplements, personal care', '2026-04-04 02:44:36', NULL),
(7, 'Home & Living', 'Furniture, décor, kitchenware', '2026-04-04 02:44:36', NULL),
(8, 'Sports & Outdoors', 'Equipment, camping, fitness', '2026-04-04 02:44:36', NULL),
(9, 'Toys & Games', 'Children’s toys, board games', '2026-04-04 02:44:36', NULL),
(10, 'Automotive', 'Car parts, accessories, tools', '2026-04-04 02:44:36', NULL),
(11, 'Office Supplies', 'Stationery, printers, paper', '2026-04-04 02:44:36', NULL),
(12, 'Books & Media', 'Books, magazines, DVDs', '2026-04-04 02:44:36', NULL),
(13, 'Pet Supplies', 'Food, toys, grooming products', '2026-04-04 02:44:36', NULL),
(14, 'Baby Products', 'Diapers, formula, strollers', '2026-04-04 02:44:36', NULL),
(15, 'Tools & Hardware', 'Power tools, hand tools, fasteners', '2026-04-04 02:44:36', NULL),
(16, 'Jewelry & Watches', 'Accessories, watches, gems', '2026-04-04 02:44:36', NULL),
(17, 'Computers & IT', 'Laptops, desktops, peripherals', '2026-04-04 02:44:36', NULL),
(18, 'Mobile Phones', 'Smartphones, tablets, chargers', '2026-04-04 02:44:36', NULL),
(19, 'Software', 'Licenses, antivirus, apps', '2026-04-04 02:44:36', NULL),
(20, 'Medical Supplies', 'First aid, equipment, disposables', '2026-04-04 02:44:36', NULL),
(21, 'Furniture', 'Chairs, tables, cabinets', '2026-04-04 02:44:36', NULL),
(22, 'Uncategorized', 'Products without a specific category', '2026-04-04 02:44:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `attempt_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `buying_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `min_stock` int(11) NOT NULL DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `sku`, `category_id`, `buying_price`, `selling_price`, `stock`, `min_stock`, `image`, `status`, `created_at`, `updated_at`) VALUES
(2, 'juice', '3', NULL, 20.00, 150.00, 14, 5, '1775292200_5bae6585a503d8ef104e.jpg', 'active', '2026-04-04 08:43:20', '2026-04-04 11:46:08'),
(3, 'aswang', '1', NULL, 12.00, 20.00, 125, 5, NULL, 'active', '2026-04-04 09:06:17', '2026-04-04 11:52:29'),
(5, 'agogo', '90', 12, 20.00, 13.00, 120, 5, NULL, 'active', '2026-04-04 11:00:53', '2026-04-04 11:00:53');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_no` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `tax` decimal(10,2) DEFAULT 0.00,
  `grand_total` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','card','online') DEFAULT 'cash',
  `sale_date` datetime DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `invoice_no`, `user_id`, `customer_name`, `total_amount`, `discount`, `tax`, `grand_total`, `payment_method`, `sale_date`, `created_at`, `updated_at`) VALUES
(1, 'INV-20260404091059889', 1, '', 320.00, 0.00, 0.00, 320.00, 'cash', '2026-04-04 09:10:59', '2026-04-04 02:10:59', NULL),
(2, 'INV-20260404091333481', 1, '', 170.00, 0.00, 0.00, 170.00, 'cash', '2026-04-04 09:13:33', '2026-04-04 02:13:33', NULL),
(3, 'INV-20260404091547456', 1, '', 170.00, 0.00, 0.00, 170.00, 'cash', '2026-04-04 09:15:47', '2026-04-04 02:15:47', NULL),
(4, 'INV-20260404092817786', 1, '', 320.00, 0.00, 0.00, 320.00, 'cash', '2026-04-04 09:28:17', '2026-04-04 02:28:17', NULL),
(5, 'INV-20260404104027941', 1, '', 170.00, 0.00, 0.00, 170.00, 'cash', '2026-04-04 10:40:27', '2026-04-04 03:40:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` int(11) NOT NULL,
  `sale_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `price`, `total`) VALUES
(1, 1, 2, 2, 150.00, 300.00),
(2, 2, 3, 1, 20.00, 20.00),
(3, 3, 3, 1, 20.00, 20.00),
(4, 3, 2, 1, 150.00, 150.00),
(5, 4, 3, 1, 20.00, 20.00),
(6, 4, 2, 2, 150.00, 300.00),
(7, 5, 2, 1, 150.00, 150.00),
(8, 5, 3, 1, 20.00, 20.00);

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `type` enum('in','out','adjust') NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `quantity`, `type`, `reference`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'out', 'INV-20260404091547456', 1, '2026-04-04 09:15:47', '2026-04-04 09:15:47'),
(2, 2, 1, 'out', 'INV-20260404091547456', 1, '2026-04-04 09:15:47', '2026-04-04 09:15:47'),
(3, 3, 1, 'out', 'INV-20260404092817786', 1, '2026-04-04 09:28:17', '2026-04-04 09:28:17'),
(4, 2, 2, 'out', 'INV-20260404092817786', 1, '2026-04-04 09:28:17', '2026-04-04 09:28:17'),
(5, 3, 322, 'in', '', 1, '2026-04-04 10:15:15', '2026-04-04 10:15:15'),
(6, 2, 1, 'out', 'INV-20260404104027941', 1, '2026-04-04 10:40:27', '2026-04-04 10:40:27'),
(7, 3, 1, 'out', 'INV-20260404104027941', 1, '2026-04-04 10:40:27', '2026-04-04 10:40:27'),
(8, 3, 123, 'in', '', 1, '2026-04-04 11:52:29', '2026-04-04 11:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `status` enum('active','inactive') DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `phone`, `password`, `role`, `status`, `avatar`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'john', '', 'smoshiee34@gmail.com', NULL, '$2y$10$CmCoxsObm7d12U1fw3HrQOr4Vzl/gQ9Lu/0Cil29baNbtpMRXHWUm', 'admin', 'active', NULL, '2026-04-04 08:17:43', '2026-04-04 08:17:43', NULL),
(2, 'serjohn', '', 'smoshiee304@gmail.com', '', '$2y$10$9ts1B3d6Buyapa7WJmmzHOFEPbeGKNAxtbISQznSLJapkbpBzkV2m', 'admin', 'active', NULL, '2026-04-04 08:18:18', '2026-04-04 10:38:41', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_id` (`sale_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
