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
  `idUser` INT(11) NOT NULL AUTO_INCREMENT,
  `userType` VARCHAR(30) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `fname` VARCHAR(45) NOT NULL,
  `lname` VARCHAR(45) NOT NULL,
  `sex` VARCHAR(6) NOT NULL,
  `birthdate` DATE NOT NULL,
  `address` VARCHAR(150) NULL DEFAULT NULL,
  `contactNo` VARCHAR(12) NULL DEFAULT NULL,
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`employer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employer` (
  `idEmployer` INT(11) NOT NULL AUTO_INCREMENT,
  `profilePic` MEDIUMBLOB NULL DEFAULT NULL,
  `validId` MEDIUMBLOB NULL DEFAULT NULL,
  `verifyStatus` VARCHAR(45) NOT NULL DEFAULT 'Not Verified',
  `idUser` INT(11) NOT NULL,
  PRIMARY KEY (`idEmployer`),
  INDEX `fk_employer_users1_idx` (`idUser` ASC) ,
  CONSTRAINT `fk_employer_users1`
    FOREIGN KEY (`idUser`)
    REFERENCES `house_connect`.`user` (`idUser`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`worker_documents`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker_documents` (
  `idWorkerDocuments` INT(11) NOT NULL AUTO_INCREMENT,
  `curriculumVitae` MEDIUMBLOB NOT NULL,
  `validID` MEDIUMBLOB NOT NULL,
  `nbi` MEDIUMBLOB NULL,
  `medical` MEDIUMBLOB NULL,
  `certificate` MEDIUMBLOB NULL DEFAULT NULL,
  PRIMARY KEY (`idWorkerDocuments`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`worker`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker` (
  `idWorker` INT(11) NOT NULL AUTO_INCREMENT,
  `workerType` VARCHAR(45) NOT NULL,
  `workerStatus` VARCHAR(45) NOT NULL,
  `profilePic` MEDIUMBLOB NULL DEFAULT NULL,
  `verifyStatus` VARCHAR(45) NOT NULL,
  `yearsOfExperience` INT(11) NOT NULL,
  `height` INT(11) NOT NULL,
  `paypalEmail` VARCHAR(45) NULL DEFAULT NULL,
  `idWorkerDocuments` INT(11) NULL DEFAULT NULL,
  `idUser` INT(11) NOT NULL,
  PRIMARY KEY (`idWorker`),
  INDEX `fk_Worker_Users_idx` (`idUser` ASC) ,
  INDEX `fk_worker_worker_documents1_idx` (`idWorkerDocuments` ASC) ,
  CONSTRAINT `fk_Worker_Users`
    FOREIGN KEY (`idUser`)
    REFERENCES `house_connect`.`user` (`idUser`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_worker_worker_documents1`
    FOREIGN KEY (`idWorkerDocuments`)
    REFERENCES `house_connect`.`worker_documents` (`idWorkerDocuments`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`contract`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`contract` (
  `idContract` INT(11) NOT NULL AUTO_INCREMENT,
  `contractStatus` VARCHAR(45) NOT NULL,
  `startDate` DATE NULL DEFAULT NULL,
  `endDate` DATE NULL DEFAULT NULL,
  `salaryAmt` DECIMAL(8,2) NULL DEFAULT NULL,
  `contractImg` MEDIUMBLOB NULL DEFAULT NULL,
  `date_created` TIMESTAMP(1) NOT NULL DEFAULT CURRENT_TIMESTAMP(1) ON UPDATE CURRENT_TIMESTAMP(1),
  `idWorker` INT(11) NOT NULL,
  `idEmployer` INT(11) NOT NULL,
  PRIMARY KEY (`idContract`),
  INDEX `fk_contract_worker1_idx` (`idWorker` ASC) ,
  INDEX `fk_contract_employer1_idx` (`idEmployer` ASC) ,
  CONSTRAINT `fk_contract_employer1`
    FOREIGN KEY (`idEmployer`)
    REFERENCES `house_connect`.`employer` (`idEmployer`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_contract_worker1`
    FOREIGN KEY (`idWorker`)
    REFERENCES `house_connect`.`worker` (`idWorker`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`employer_payment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employer_payment` (
  `idEmployerPayment` INT(11) NOT NULL AUTO_INCREMENT,
  `amount` DECIMAL(8,2) NOT NULL,
  `method` VARCHAR(45) NOT NULL,
  `imgReceipt` MEDIUMBLOB NOT NULL,
  `paymentStatus` VARCHAR(45) NOT NULL,
  `submitted_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `idContract` INT(11) NOT NULL,
  PRIMARY KEY (`idEmployerPayment`),
  INDEX `fk_payment_contract1_idx` (`idContract` ASC) ,
  CONSTRAINT `fk_payment_contract1`
    FOREIGN KEY (`idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`employer_request`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`employer_request` (
  `idEmployerPreference` INT(11) NOT NULL AUTO_INCREMENT,
  `workerType` VARCHAR(45) NOT NULL,
  `age` VARCHAR(45) NULL DEFAULT NULL,
  `height` INT(11) NULL DEFAULT NULL,
  `yrsOfExperience` INT(11) NULL DEFAULT NULL,
  `additionalMessage` VARCHAR(250) NULL,
  `status` VARCHAR(45) NOT NULL,
  `date_requested` VARCHAR(45) NOT NULL,
  `employer_idEmployer` INT(11) NOT NULL,
  `contract_idContract` INT(11) NOT NULL,
  PRIMARY KEY (`idEmployerPreference`),
  INDEX `fk_employerPreference_employer1_idx` (`employer_idEmployer` ASC) ,
  INDEX `fk_employerpreference_contract1_idx` (`contract_idContract` ASC) ,
  CONSTRAINT `fk_employerPreference_employer1`
    FOREIGN KEY (`employer_idEmployer`)
    REFERENCES `house_connect`.`employer` (`idEmployer`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_employerpreference_contract1`
    FOREIGN KEY (`contract_idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`meeting`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`meeting` (
  `idMeeting` INT(11) NOT NULL AUTO_INCREMENT,
  `meetDate` DATETIME NOT NULL,
  `locationAddress` VARCHAR(45) NOT NULL,
  `contract_idContract` INT(11) NOT NULL,
  PRIMARY KEY (`idMeeting`),
  INDEX `fk_meeting_contract1_idx` (`contract_idContract` ASC) ,
  CONSTRAINT `fk_meeting_contract1`
    FOREIGN KEY (`contract_idContract`)
    REFERENCES `house_connect`.`contract` (`idContract`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `house_connect`.`worker_salary`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `house_connect`.`worker_salary` (
  `idWorkerSalary` INT(11) NOT NULL AUTO_INCREMENT,
  `paypalEmail` VARCHAR(45) NOT NULL,
  `tax_amount` DECIMAL(8,2) NOT NULL,
  `amount` DECIMAL(8,2) NOT NULL,
  `net_pay` DECIMAL(8,2) NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  `modified_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
  `idEmployerPayment` INT(11) NOT NULL,
  PRIMARY KEY (`idWorkerSalary`),
  INDEX `fk_worker_salary_employer_payment1_idx` (`idEmployerPayment` ASC) ,
  CONSTRAINT `fk_worker_salary_employer_payment1`
    FOREIGN KEY (`idEmployerPayment`)
    REFERENCES `house_connect`.`employer_payment` (`idEmployerPayment`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

INSERT INTO user (userType, email, password, fname, lname, sex, birthdate, contactNo) 
VALUES ('Admin', 'houseconnect@gmail.com', '12345678', 'Administrator', '', 'Male', CURDATE(), '09999999999');

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
