-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2025 at 08:59 PM
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
-- Database: `db_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `price`, `description`, `image`) VALUES
(10, 'حلق أوراق الشجر', 5.00, 'حلق معدن مطلي بالذهب + زركون عالي الجودة ,\r\nارتقي بأناقتك مع لمسة طبيعية مستوحاة من سحر الأوراق الذهبية', '1746814618_product1.jpg'),
(11, 'سنسال الأقحوان', 7.00, 'اضيفي لمسة من النعومة والأنوثة لإطلالتك مع طوق الأقحوانة الرقيق , خيار مثالي لإطلالة ناعمة تعكس الذوق الرفيع ', '1746815158_product2.jpg'),
(12, 'قلادة شمس الأوبال', 10.00, '  قلادة فاخرة بتصميم شمس مشعة وتزينها أحجار براقة تحيط بحجر أوبال , مثالية لمن تبحث عن قطعة تجمع بين الرقي والتميز \r\n\r\n', '1746815317_product3.jpg'),
(13, 'حلق الكرز', 4.00, 'حلق أنيق مصنوع من معدن ذهبي مطلي بعناية ومرصع بأحجار كريستالية , مثالي لإضفاء طابع انوثي على الإطلالة اليومية\r\n', '1746815527_product4.jpg'),
(14, 'ساعة كلاسيكة', 15.00, 'ساعة يد نسائية فضية بتصميم كلاسيكي انيق بشكل بيضوي يحيط به اطار كريستالات ناعمة وسوار معدني كلاسيكي ', '1746828545_product5.jpg'),
(15, 'طقم فراشات', 20.00, 'طقم رباعي انيق ,مصنوع من معدن ذهبي لامع بتفاصيل ناعمة تعكس الرقي والأنوثة , مثالي لإطلالة بسيطة وهادئة', '1746831908_product6.jpg'),
(16, 'خاتم لف', 6.00, 'خاتم بتصميم مفتوح وملتف بانسيابية راقية يجمع بين النعومة والفخامة ,  قطعة استثنائية تعبر عن الأناقة والذوق الرفيع', '1746829696_product7.jpg'),
(17, 'طقم الفراشة', 12.00, 'طق ثنائي ناعم وبسيط مكون من سنسال ناعم وحلق جذاب يجمع بين النعومة والفخامة , يعطي الشعور بالحرية والحب\r\n', '1746829879_product8.jpg'),
(18, 'خاتم زهرة القمر ', 6.00, 'خاتم بتصميم انثوي رقيق تزينه وردة بلون زهري ناعم يعطي الفخامة والانوثة لليد ويضيف إشراقة لإطلالتك', '1746830090_product9.jpg'),
(19, 'طقم نجوم', 14.00, 'طقم ناعم ورقيق بتصميم نجمات لامعة لطيفة ,يعطي لمسة هادية وجذابة لأي إطلالة ,سواء للمناسبات او للستايل اليومي ', '1746831152_product10.jpg'),
(20, 'حلق فيونكة', 4.00, 'حلق لطيف بتصميم فيونكات حمراء لامعة , يجمع بين النعومة والمرح ويعطي اطلالة مميزة بناتية مبهجة ', '1746831387_product11.jpg'),
(21, 'سنسال الهلال', 7.00, 'سنسال بتصميم بسيط وجذاب لهلال ذهبي يعطي الجمال لأي إطلالة , يعكس الهدوء والأنوثة والنعومة\r\n', '1746831509_product12.jpg'),
(22, 'قلادة الصدفة واللؤلؤة', 8.00, 'قلادة ذهبية ناعمة ومميزة بتصميم صدفة مفتوحة مع لؤلؤة لامعة بلون طبيعي , لها تفاصيل ساحرة وتعطي شعور الهدوء ', '1746831641_product13.jpg'),
(23, 'حلق ورود', 4.00, 'حلق بأربع بتلات بيضاء ناعمة يجمع بين الاناقة والعفوية مناسب لإطلالة يومية بسيطة ناعمة', '1746832440_product15.jpg'),
(24, 'سوار فراشات', 5.00, 'سوار بسيط ناعم مزين بالفراشات الناعمة والرقيقة يجمع بين النعومة والاناقة , مثالي لإضافة لمسة خفيفة وجذابة لليد', '1746832540_product18.jpg'),
(25, 'طوق الكرزة', 6.00, 'عقد ذهبي بتعليقة كزرة لامعة , بأحجار حمراء ولمسة خضراء أنيقة  وتصميم لطيف يعكس الأنوثة', '1746832676_product19.jpg'),
(26, 'حلق غصن القمر', 4.00, 'تصميم يجمع بين النعومة والأنوثة والرقة بشبكة انيقة من المعدن الذهبي المنحني , يعطي لمسة ناعمة وانيقة ', '1746833875_product20.jpg'),
(27, 'سوار قلوب', 5.00, 'سوار رقيق من الذهب اللامع مع قلوب بلون لؤلؤي ناعم يعكس ضوء خفيف على اليد , مناسب لإطلالة يومية بسيطة ', '1746834486_product21.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(8, 'admin', 'admin@gmail.com', '$2y$10$zo8dukKSFzhPNhjtkvpHx.eC98.51l14.PTzwBnKp0Psa5.mbqPaO', 'admin'),
(9, 'joudi', 'joudi@gmail.com', '$2y$10$3pjbKtVwdh5VYUi7SzWqteOIi0A/nRWnehGugexeQFcty0hG5NcLG', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
