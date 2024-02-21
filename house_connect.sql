-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema house_connect
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema house_connect
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `house_connect` DEFAULT CHARACTER SET utf8 ;
USE `house_connect` ;

-- -----------------------------------------------------
-- Table `house_connect`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`user` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `userType` VARCHAR(30) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `fname` VARCHAR(45) NOT NULL,
  `lname` VARCHAR(45) NOT NULL,
  `sex` VARCHAR(6) NOT NULL,
  `birthdate` DATE NOT NULL,
  `address` VARCHAR(150) NULL,
  `contactNo` VARCHAR(12) NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`worker_documents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker_documents` (
  `idWorkerDocuments` INT NOT NULL,
  `curriculumVitae` MEDIUMBLOB NOT NULL,
  `validID` MEDIUMBLOB NOT NULL,
  `nbi` MEDIUMBLOB NOT NULL,
  `medical` MEDIUMBLOB NOT NULL,
  `certificate` MEDIUMBLOB NULL,
  PRIMARY KEY (`idWorkerDocuments`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`worker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker` (
  `idWorker` INT NOT NULL AUTO_INCREMENT,
  `workerType` VARCHAR(45) NOT NULL,
  `workerStatus` VARCHAR(45) NOT NULL,
  `profilePic` MEDIUMBLOB NULL,
  `verifyStatus` VARCHAR(45) NOT NULL,
  `yearsOfExperience` INT NOT NULL,
  `height` INT NOT NULL,
  `paypalEmail` VARCHAR(45) NULL,
  `idWorkerDocuments` INT NULL,
  `idUser` INT NOT NULL,
  PRIMARY KEY (`idWorker`),
  INDEX `fk_Worker_Users_idx` (`idUser` ASC) ,
  INDEX `fk_worker_worker_documents1_idx` (`idWorkerDocuments` ASC) ,
  CONSTRAINT `fk_Worker_Users`
    FOREIGN KEY (`idUser`)
    REFERENCES `house_connect`.`user` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_worker_worker_documents1`
    FOREIGN KEY (`idWorkerDocuments`)
    REFERENCES `house_connect`.`worker_documents` (`idWorkerDocuments`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`employer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employer` (
  `idEmployer` INT NOT NULL AUTO_INCREMENT,
  `profilePic` VARCHAR(45) NULL,
  `validId` MEDIUMBLOB NULL,
  `verifyStatus` VARCHAR(45) NOT NULL DEFAULT 'Not Verified',
  `idUser` INT NOT NULL,
  PRIMARY KEY (`idEmployer`),
  INDEX `fk_employer_users1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_employer_users1`
    FOREIGN KEY (`idUser`)
    REFERENCES `house_connect`.`user` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`contract`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`contract` (
  `idContract` INT NOT NULL AUTO_INCREMENT,
  `contractStatus` VARCHAR(45) NOT NULL,
  `startDate` DATE NULL,
  `endDate` DATE NULL,
  `salarytAmt` DECIMAL(2) NULL,
  `contractImg` MEDIUMBLOB NULL,
  `date_created` TIMESTAMP(1) NOT NULL,
  `idWorker` INT NOT NULL,
  `idEmployer` INT NOT NULL,
  PRIMARY KEY (`idContract`),
  INDEX `fk_contract_worker1_idx` (`idWorker` ASC) ,
  INDEX `fk_contract_employer1_idx` (`idEmployer` ASC) ,
  CONSTRAINT `fk_contract_worker1`
    FOREIGN KEY (`idWorker`)
    REFERENCES `house_connect`.`worker` (`idWorker`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contract_employer1`
    FOREIGN KEY (`idEmployer`)
    REFERENCES `house_connect`.`employer` (`idEmployer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`employer_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employer_payment` (
  `idEmployerPayment` INT NOT NULL AUTO_INCREMENT,
  `amount` DECIMAL(2) NOT NULL,
  `method` VARCHAR(45) NOT NULL,
  `imgReceipt` MEDIUMBLOB NOT NULL,
  `paymentStatus` VARCHAR(45) NOT NULL,
  `submitted_at` TIMESTAMP NOT NULL,
  `idContract` INT NOT NULL,
  PRIMARY KEY (`idEmployerPayment`),
  INDEX `fk_payment_contract1_idx` (`idContract` ASC) ,
  CONSTRAINT `fk_payment_contract1`
    FOREIGN KEY (`idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`employerPreference`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employerPreference` (
  `idEmployerPreference` INT NOT NULL AUTO_INCREMENT,
  `workerType` VARCHAR(45) NOT NULL,
  `ageMin` INT NULL,
  `ageMax` VARCHAR(45) NULL,
  `minHeight` INT NULL,
  `maxHeight` INT NULL,
  `yrsOfExperience` INT NULL,
  `employer_idEmployer` INT NOT NULL,
  PRIMARY KEY (`idEmployerPreference`),
  INDEX `fk_employerPreference_employer1_idx` (`employer_idEmployer` ASC) ,
  CONSTRAINT `fk_employerPreference_employer1`
    FOREIGN KEY (`employer_idEmployer`)
    REFERENCES `house_connect`.`employer` (`idEmployer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`meeting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`meeting` (
  `idMeeting` INT NOT NULL AUTO_INCREMENT,
  `meetDate` DATETIME NOT NULL,
  `platform` VARCHAR(45) NOT NULL,
  `link` VARCHAR(255) NOT NULL,
  `contract_idContract` INT NOT NULL,
  PRIMARY KEY (`idMeeting`),
  INDEX `fk_meeting_contract1_idx` (`contract_idContract` ASC) ,
  CONSTRAINT `fk_meeting_contract1`
    FOREIGN KEY (`contract_idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`admin_message`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`admin_message` (
  `idMessage` INT NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(100) NOT NULL,
  `message` VARCHAR(1000) NOT NULL,
  `isRead` TINYINT(1) NOT NULL,
  `idUser` INT NOT NULL,
  PRIMARY KEY (`idMessage`),
  INDEX `fk_admin_message_user1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_admin_message_user1`
    FOREIGN KEY (`idUser`)
    REFERENCES `house_connect`.`user` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`rating`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`rating` (
  `idRating` INT NOT NULL AUTO_INCREMENT,
  `rate` INT NULL,
  `comment` VARCHAR(45) NULL,
  `idContract` INT NOT NULL,
  PRIMARY KEY (`idRating`),
  INDEX `fk_rating_contract1_idx` (`idContract` ASC) ,
  CONSTRAINT `fk_rating_contract1`
    FOREIGN KEY (`idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `house_connect`.`worker_salary`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker_salary` (
  `idWorkerSalary` INT NOT NULL AUTO_INCREMENT,
  `paypalEmail` VARCHAR(45) NOT NULL,
  `amount` DECIMAL(2) NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  `modified_at` TIMESTAMP NOT NULL,
  `idEmployerPayment` INT NOT NULL,
  PRIMARY KEY (`idWorkerSalary`),
  INDEX `fk_worker_salary_employer_payment1_idx` (`idEmployerPayment` ASC) ,
  CONSTRAINT `fk_worker_salary_employer_payment1`
    FOREIGN KEY (`idEmployerPayment`)
    REFERENCES `house_connect`.`employer_payment` (`idEmployerPayment`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;