-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2021 at 11:00 PM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(3) UNSIGNED NOT NULL,
  `cat_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_title`) VALUES
(1, 'Bootstrap'),
(4, 'Laravel'),
(16, 'React'),
(18, 'Angular');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) UNSIGNED NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_author_id` varchar(191) NOT NULL,
  `comment_content` varchar(1000) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `comment_author_id`, `comment_content`, `comment_date`) VALUES
(68, 39, '7', 'კომენტარი', '2021-07-26 12:28:45'),
(113, 34, '7', 'comment', '2021-08-02 00:03:45'),
(118, 42, '7', 'Holy fuck', '2021-08-04 10:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(3) UNSIGNED NOT NULL,
  `post_author_id` varchar(255) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_category_id` varchar(191) NOT NULL,
  `post_image` text NOT NULL,
  `post_tags` varchar(255) NOT NULL,
  `post_views` int(11) NOT NULL DEFAULT 0,
  `post_content` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_author_id`, `post_title`, `post_category_id`, `post_image`, `post_tags`, `post_views`, `post_content`, `post_date`) VALUES
(17, '7', 'Bootsrap is cool', '16', 'image_3.jpg', 'bootstrap, cool', 0, 'This is bootstrap post', '2021-07-15 19:00:30'),
(18, '4', 'PHP', '16', '1_0a0gVNRnVq6AlotWrxeX2A.png', 'php, back, laravel', 1, 'PHP is used for back-end', '2021-07-15 19:00:32'),
(19, '4', 'Javascript', '1', 'image_4.jpg', 'javascript, react', 0, 'Javascript is used for front.', '2021-07-15 19:00:36'),
(20, '4', 'Survey form', '16', 'image_2.jpg', 'angular, survey, form', 11, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto laudantium placeat aliquid totam assumenda quaerat at vel nemo ad deserunt. Officiis sit quas illo a. Voluptate, incidunt. Est, aperiam deleniti.', '2021-07-15 19:00:39'),
(21, '7', 'This is test', '4', 'image_1.jpg', 'test, post', 0, 'This is test.', '2021-07-15 19:01:01'),
(22, '7', 'Bootsrap is cool', '1', 'image_3.jpg', 'bootstrap, cool', 0, 'This is bootstrap post', '2021-07-15 19:01:04'),
(23, '4', 'PHP', '1', '1_0a0gVNRnVq6AlotWrxeX2A.png', 'php, back, laravel', 0, 'PHP is used for back-end', '2021-07-15 19:01:06'),
(24, '4', 'Javascript', '18', 'image_4.jpg', 'javascript, react', 0, 'Javascript is used for front.', '2021-07-15 19:01:08'),
(25, '4', 'Survey form', '1', 'image_2.jpg', 'angular, survey, form', 0, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto laudantium placeat aliquid totam assumenda quaerat at vel nemo ad deserunt. Officiis sit quas illo a. Voluptate, incidunt. Est, aperiam deleniti.', '2021-07-15 19:01:11'),
(26, '7', 'This is test', '4', 'image_1.jpg', 'test, post', 0, 'This is test.', '2021-07-15 19:16:50'),
(27, '7', 'Bootsrap is cool', '16', 'image_3.jpg', 'bootstrap, cool', 0, 'This is bootstrap post', '2021-07-15 19:44:06'),
(28, '4', 'PHP', '16', '1_0a0gVNRnVq6AlotWrxeX2A.png', 'php, back, laravel', 3, 'PHP is used for back-end', '2021-07-15 19:44:21'),
(29, '4', 'Javascript', '1', 'image_4.jpg', 'javascript, react', 1, 'Javascript is used for front.', '2021-07-15 19:44:24'),
(30, '4', 'Survey form', '16', 'image_2.jpg', 'angular, survey, form', 0, 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto laudantium placeat aliquid totam assumenda quaerat at vel nemo ad deserunt. Officiis sit quas illo a. Voluptate, incidunt. Est, aperiam deleniti.', '2021-07-15 19:44:27'),
(31, '7', 'This is test', '4', 'image_1.jpg', 'test, post', 1, 'This is test.', '2021-07-15 19:44:33'),
(32, '7', 'Bootsrap is cool', '16', 'image_3.jpg', 'bootstrap, cool', 3, 'This is bootstrap post', '2021-07-15 23:07:34'),
(34, '7', 'This is test', '1', 'image_1.jpg', 'test, post', 18, 'This is test.', '2021-07-15 23:35:37'),
(35, '7', 'Bootsrap is cool', '1', 'image_3.jpg', 'bootstrap, cool', 7, 'This is bootstrap post', '2021-07-15 23:35:56'),
(36, '7', 'Fight Club', '16', 'il_fullxfull.1325571098_p21w.jpg', 'fight, club', 129, 'First rule of Fight Club is you do not talk about fight club.', '2021-07-15 23:36:28'),
(39, '4', 'Javascript', '1', 'image_4.jpg', 'javascript, react', 9, 'Javascript is used for front.', '2021-07-23 21:43:19'),
(42, '7', 'Brand New', '4', 'image_3.jpg', 'brand, new', 98, 'This is brand new post', '2021-07-23 22:53:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `username` varchar(191) NOT NULL,
  `fname` varchar(191) NOT NULL,
  `lname` varchar(191) NOT NULL,
  `bdate` date NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(191) NOT NULL,
  `image` varchar(255) NOT NULL,
  `role` varchar(191) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fname`, `lname`, `bdate`, `email`, `password`, `image`, `role`) VALUES
(4, 'JohnDoe', 'John', 'Doe', '1990-06-14', 'JohnDoe@gmail.com', '$2y$10$8sEy.9vMMHSaNY38DQOHIOU6qWVf0iwIBxlWPzkrN4gcQ9b8rfV7K', 'il_fullxfull.1325571098_p21w.jpg', 'Moderator'),
(7, 'NIK0LADZE', 'Giorgi', 'Nikoladze', '1998-08-13', 'g.nikoladze13@gmail.com', '$2y$10$fouijMwTDuYU4kfiQERZ5OVxohQKGKfdEan5s8fGvqXYvDZPaz6qu', '145785847_3770910296281126_9209878888767239630_n.jpg', 'Admin'),
(16, 'tsats2013', 'Tornike', 'Satseradze', '1995-01-25', 'satseradzetornike@gmail.com', '$2y$10$aVCsdaFSbyGQ1SNPXBH.Buh.ZQJYDjbmOEQzekVTuqMcGT2dALiaO', 'no-photo.png', 'Subscriber'),
(22, 'Goofy', 'John', 'Wick', '1990-08-08', 'goofy@gmail.com', '$2y$10$PksEht7wzWlEodRF2DcL4ehDRNI29pUnm4SjKUiDuvuntH0e1BaEa', 'no-photo.png', 'Moderator'),
(23, 'Sherlock', 'Sherlock', 'Holmes', '1958-07-01', 'sherlock@gmail.com', '$2y$10$f7U9zTgcTK303KDQBCVM6OyJmxTX.2nlcGojAWFTPNozD/1C4AEwu', 'no-photo.png', 'Moderator'),
(24, 'Gimmick', 'Jimmy', 'Hoffa', '1978-08-03', 'hoffa@gmail.com', '$2y$10$tgdmQa4JHjl7VnjREpQxLu7OVK7SutvfEKY7NKz0gGTABS3CsVz56', 'no-photo.png', 'Subscriber'),
(30, 'Biggie', 'Christopher', 'Wallace', '1990-08-08', 'biggie@gmail.com', '$2y$10$6b3SpiLw5pJLh7t5QmTURut4pyGBsouNdQXsh5.gfsYf2UUOBHm8q', 'no-photo.png', 'Subscriber'),
(31, '2Pac', 'Tupac', 'Shakur', '1971-08-08', 'makaveli@gmail.com', '$2y$10$5NcQcBxtV6EX.FAlMR43jOnFTJIasNwwhP4EjiKzYiC1/OYPgstsO', 'no-photo.png', 'Subscriber'),
(32, 'Scarface', 'Al', 'Pacino', '1952-08-08', 'scarface@gmail.com', '$2y$10$ntZ6G.S2fvZybzTGd22JUejKBmTpZHnLqj3svHzKHGf9Tc4YmUMZK', 'no-photo.png', 'Subscriber');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
