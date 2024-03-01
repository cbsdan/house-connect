-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2024 at 12:37 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `house_connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_message`
--

CREATE TABLE `admin_message` (
  `idMessage` int(11) NOT NULL,
  `subject` varchar(100) NOT NULL,
  `message` varchar(1000) NOT NULL,
  `isRead` tinyint(1) NOT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `idContract` int(11) NOT NULL,
  `contractStatus` varchar(45) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `salarytAmt` decimal(2,0) DEFAULT NULL,
  `contractImg` mediumblob DEFAULT NULL,
  `date_created` timestamp(1) NOT NULL DEFAULT current_timestamp(1) ON UPDATE current_timestamp(1),
  `idWorker` int(11) NOT NULL,
  `idEmployer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employer`
--

CREATE TABLE `employer` (
  `idEmployer` int(11) NOT NULL,
  `profilePic` mediumblob DEFAULT NULL,
  `validId` mediumblob DEFAULT NULL,
  `verifyStatus` varchar(45) NOT NULL DEFAULT 'Not Verified',
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employerpreference`
--

CREATE TABLE `employerpreference` (
  `idEmployerPreference` int(11) NOT NULL,
  `workerType` varchar(45) NOT NULL,
  `ageMin` int(11) DEFAULT NULL,
  `ageMax` varchar(45) DEFAULT NULL,
  `minHeight` int(11) DEFAULT NULL,
  `maxHeight` int(11) DEFAULT NULL,
  `yrsOfExperience` int(11) DEFAULT NULL,
  `employer_idEmployer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employer_payment`
--

CREATE TABLE `employer_payment` (
  `idEmployerPayment` int(11) NOT NULL,
  `amount` decimal(2,0) NOT NULL,
  `method` varchar(45) NOT NULL,
  `imgReceipt` mediumblob NOT NULL,
  `paymentStatus` varchar(45) NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idContract` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `idMeeting` int(11) NOT NULL,
  `meetDate` datetime NOT NULL,
  `platform` varchar(45) NOT NULL,
  `link` varchar(255) NOT NULL,
  `employerMessage` varchar(150) NOT NULL,
  `contract_idContract` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE `rating` (
  `idRating` int(11) NOT NULL,
  `rate` int(11) DEFAULT NULL,
  `comment` varchar(45) DEFAULT NULL,
  `idContract` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `userType` varchar(30) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `fname` varchar(45) NOT NULL,
  `lname` varchar(45) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(150) DEFAULT NULL,
  `contactNo` varchar(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`idUser`, `userType`, `email`, `password`, `fname`, `lname`, `sex`, `birthdate`, `address`, `contactNo`) VALUES
(2, 'Admin', 'houseconnect@gmail.com', '12345678', 'Administrator', ' ', 'Male', '2024-03-01', 'Taguig City', '09999999999');

-- --------------------------------------------------------

--
-- Table structure for table `worker`
--

CREATE TABLE `worker` (
  `idWorker` int(11) NOT NULL,
  `workerType` varchar(45) NOT NULL,
  `workerStatus` varchar(45) NOT NULL,
  `profilePic` mediumblob DEFAULT NULL,
  `verifyStatus` varchar(45) NOT NULL,
  `yearsOfExperience` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `paypalEmail` varchar(45) DEFAULT NULL,
  `idWorkerDocuments` int(11) DEFAULT NULL,
  `idUser` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_documents`
--

CREATE TABLE `worker_documents` (
  `idWorkerDocuments` int(11) NOT NULL,
  `curriculumVitae` mediumblob NOT NULL,
  `validID` mediumblob NOT NULL,
  `nbi` mediumblob NOT NULL,
  `medical` mediumblob NOT NULL,
  `certificate` mediumblob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `worker_salary`
--

CREATE TABLE `worker_salary` (
  `idWorkerSalary` int(11) NOT NULL,
  `paypalEmail` varchar(45) NOT NULL,
  `amount` decimal(2,0) NOT NULL,
  `status` varchar(45) NOT NULL,
  `modified_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idEmployerPayment` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_message`
--
ALTER TABLE `admin_message`
  ADD PRIMARY KEY (`idMessage`),
  ADD KEY `fk_admin_message_user1_idx` (`idUser`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`idContract`),
  ADD KEY `fk_contract_worker1_idx` (`idWorker`),
  ADD KEY `fk_contract_employer1_idx` (`idEmployer`);

--
-- Indexes for table `employer`
--
ALTER TABLE `employer`
  ADD PRIMARY KEY (`idEmployer`),
  ADD KEY `fk_employer_users1_idx` (`idUser`);

--
-- Indexes for table `employerpreference`
--
ALTER TABLE `employerpreference`
  ADD PRIMARY KEY (`idEmployerPreference`),
  ADD KEY `fk_employerPreference_employer1_idx` (`employer_idEmployer`);

--
-- Indexes for table `employer_payment`
--
ALTER TABLE `employer_payment`
  ADD PRIMARY KEY (`idEmployerPayment`),
  ADD KEY `fk_payment_contract1_idx` (`idContract`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`idMeeting`),
  ADD KEY `fk_meeting_contract1_idx` (`contract_idContract`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`idRating`),
  ADD KEY `fk_rating_contract1_idx` (`idContract`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indexes for table `worker`
--
ALTER TABLE `worker`
  ADD PRIMARY KEY (`idWorker`),
  ADD KEY `fk_Worker_Users_idx` (`idUser`),
  ADD KEY `fk_worker_worker_documents1_idx` (`idWorkerDocuments`);

--
-- Indexes for table `worker_documents`
--
ALTER TABLE `worker_documents`
  ADD PRIMARY KEY (`idWorkerDocuments`);

--
-- Indexes for table `worker_salary`
--
ALTER TABLE `worker_salary`
  ADD PRIMARY KEY (`idWorkerSalary`),
  ADD KEY `fk_worker_salary_employer_payment1_idx` (`idEmployerPayment`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_message`
--
ALTER TABLE `admin_message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `idContract` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employer`
--
ALTER TABLE `employer`
  MODIFY `idEmployer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employerpreference`
--
ALTER TABLE `employerpreference`
  MODIFY `idEmployerPreference` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employer_payment`
--
ALTER TABLE `employer_payment`
  MODIFY `idEmployerPayment` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `idMeeting` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `idRating` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `worker`
--
ALTER TABLE `worker`
  MODIFY `idWorker` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_documents`
--
ALTER TABLE `worker_documents`
  MODIFY `idWorkerDocuments` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `worker_salary`
--
ALTER TABLE `worker_salary`
  MODIFY `idWorkerSalary` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_message`
--
ALTER TABLE `admin_message`
  ADD CONSTRAINT `fk_admin_message_user1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `contract`
--
ALTER TABLE `contract`
  ADD CONSTRAINT `fk_contract_employer1` FOREIGN KEY (`idEmployer`) REFERENCES `employer` (`idEmployer`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_contract_worker1` FOREIGN KEY (`idWorker`) REFERENCES `worker` (`idWorker`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `employer`
--
ALTER TABLE `employer`
  ADD CONSTRAINT `fk_employer_users1` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `employerpreference`
--
ALTER TABLE `employerpreference`
  ADD CONSTRAINT `fk_employerPreference_employer1` FOREIGN KEY (`employer_idEmployer`) REFERENCES `employer` (`idEmployer`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `employer_payment`
--
ALTER TABLE `employer_payment`
  ADD CONSTRAINT `fk_payment_contract1` FOREIGN KEY (`idContract`) REFERENCES `contract` (`idContract`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `meeting`
--
ALTER TABLE `meeting`
  ADD CONSTRAINT `fk_meeting_contract1` FOREIGN KEY (`contract_idContract`) REFERENCES `contract` (`idContract`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `fk_rating_contract1` FOREIGN KEY (`idContract`) REFERENCES `contract` (`idContract`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `worker`
--
ALTER TABLE `worker`
  ADD CONSTRAINT `fk_Worker_Users` FOREIGN KEY (`idUser`) REFERENCES `user` (`idUser`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_worker_worker_documents1` FOREIGN KEY (`idWorkerDocuments`) REFERENCES `worker_documents` (`idWorkerDocuments`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `worker_salary`
--
ALTER TABLE `worker_salary`
  ADD CONSTRAINT `fk_worker_salary_employer_payment1` FOREIGN KEY (`idEmployerPayment`) REFERENCES `employer_payment` (`idEmployerPayment`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
