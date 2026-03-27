-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 27, 2026 at 09:12 AM
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
-- Database: `lms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) NOT NULL,
  `isbn` varchar(13) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `publication_year` year(4) DEFAULT NULL,
  `total_copies` int(11) NOT NULL DEFAULT 1,
  `available_copies` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`book_id`, `title`, `author`, `isbn`, `genre`, `publication_year`, `total_copies`, `available_copies`) VALUES
(11, 'The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 'Fiction', '1925', 5, 3),
(12, 'To Kill a Mockingbird', 'Harper Lee', '9780061120084', 'Fiction', '1960', 4, 2),
(13, '1984', 'George Orwell', '9780451524935', 'Fiction', '1949', 6, 3),
(14, 'Pride and Prejudice', 'Jane Austen', '9780141439518', 'Fiction', '0000', 3, 3),
(15, 'The Catcher in the Rye', 'J.D. Salinger', '9780316769488', 'Fiction', '1951', 4, 2),
(17, 'The Hitchhiker\'s Guide to the Galaxy', 'Douglas Adams', '9780345391803', 'Science Fiction', '1979', 4, 3),
(18, 'Ender\'s Game', 'Orson Scott Card', '9780812550702', 'Science Fiction', '1985', 3, 2),
(19, 'Foundation', 'Isaac Asimov', '9780553293357', 'Science Fiction', '1951', 4, 4),
(20, 'Neuromancer', 'William Gibson', '9780441569595', 'Science Fiction', '1984', 3, 1),
(21, 'The Da Vinci Code', 'Dan Brown', '9780307474278', 'Mystery', '2003', 6, 5),
(22, 'Murder on the Orient Express', 'Agatha Christie', '9780062693662', 'Mystery', '1934', 4, 3),
(23, 'The Girl with the Dragon Tattoo', 'Stieg Larsson', '9780307454546', 'Mystery', '2005', 5, 4),
(24, 'Gone Girl', 'Gillian Flynn', '9780307588371', 'Mystery', '2012', 4, 2),
(25, 'The Silent Patient', 'Alex Michaelides', '9781250301697', 'Mystery', '2019', 3, 3),
(26, 'Sapiens: A Brief History of Humankind', 'Yuval Noah Harari', '9780062316097', 'Non-Fiction', '2011', 5, 4),
(27, 'Educated', 'Tara Westover', '9780399590504', 'Non-Fiction', '2018', 4, 3),
(28, 'Becoming', 'Michelle Obama', '9781524763138', 'Non-Fiction', '2018', 6, 5),
(29, 'The Subtle Art of Not Giving a F*ck', 'Mark Manson', '9780062457714', 'Non-Fiction', '2016', 4, 2),
(30, 'Atomic Habit', 'James Clear', '9780735211292', 'Non-Fiction', NULL, 5, 5),
(31, 'The Hobbit', 'J.R.R. Tolkien', '9780547928227', 'Fantasy', '1937', 6, 5),
(32, 'Harry Potter and the Sorcerer\'s Stone', 'J.K. Rowling', '9780590353427', 'Fantasy', '1997', 7, 6),
(33, 'A Game of Thrones', 'George R.R. Martin', '9780553573404', 'Fantasy', '1996', 5, 3),
(34, 'The Name of the Wind', 'Patrick Rothfuss', '9780756404741', 'Fantasy', '2007', 4, 4),
(35, 'The Way of Kings', 'Brandon Sanderson', '9780765365279', 'Fantasy', '2010', 3, 2),
(36, 'Pride and Prejudice', 'Jane Austen', '9780141439518', 'Romance', '0000', 4, 3),
(37, 'Outlander', 'Diana Gabaldon', '9780440212560', 'Romance', '1991', 3, 2),
(38, 'The Notebook', 'Nicholas Sparks', '9780446676090', 'Romance', '1996', 5, 4),
(39, 'Me Before You', 'Jojo Moyes', '9780143124542', 'Romance', '2012', 4, 3),
(40, 'It Ends with Us', 'Colleen Hoover', '9781501110368', 'Romance', '2016', 6, 5);

-- --------------------------------------------------------

--
-- Table structure for table `loan`
--

CREATE TABLE `loan` (
  `loan_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('Borrowed','Returned','Overdue') NOT NULL DEFAULT 'Borrowed',
  `fine_amount` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` set('admin','librarian','member') NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(255) NOT NULL,
  `data_registered` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `password`, `role`, `phone`, `address`, `data_registered`) VALUES
(2, 'princess marben villador', 'admin@gmail.com', '$2y$10$Z96TNAMBmR/zK8k6wu0ErOIq.qZeTyL0lHUejcdFjRbIuXj3ExPcC', 'admin', '09123456789', 'Gomez, San Isidro, Isabela', '2026-03-27'),
(23, 'Juan Jose', 'juan@gmail.com', '$2y$10$18yW8gKAJPTB8uA29A0xmOk/wKher3idLjKWk3dLtB2.MK3U0mZqi', 'librarian', '09123456789', 'santiago city, isabela', '2026-03-27'),
(24, 'pedro manalo', 'pedro@gmail.com', '$2y$10$XYHZvaoOjOU.uNzl4tHzJu88U2g/XCmfllGpXd/4bHIJ2Qs4vcFJy', 'member', '09123456780', 'santiago city, isabela', '2026-03-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `loan`
--
ALTER TABLE `loan`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `fk_loan_book` (`book_id`),
  ADD KEY `fk_loan_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `loan`
--
ALTER TABLE `loan`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan`
--
ALTER TABLE `loan`
  ADD CONSTRAINT `fk_loan_book` FOREIGN KEY (`book_id`) REFERENCES `book` (`book_id`),
  ADD CONSTRAINT `fk_loan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
