-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema transactions
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema transactions
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `transactions` DEFAULT CHARACTER SET utf8 ;
USE `transactions` ;

-- -----------------------------------------------------
-- Table `transactions`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`user` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user` CHAR(50) NOT NULL,
  `password` VARCHAR(150) CHARACTER SET 'utf8mb4' NOT NULL,
  `role` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_usr_idx` (`user` ASC),
  INDEX `user_login_idx` (`user` ASC, `password` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 11
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`profile`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`profile` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `active` SMALLINT(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `profile_active_idx` (`active` ASC),
  INDEX `user_profile_fk` (`user_id` ASC),
  CONSTRAINT `user_profile_fk`
    FOREIGN KEY (`user_id`)
    REFERENCES `transactions`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 10
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`account`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`account` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `profile_id` INT(11) UNSIGNED NULL,
  `number` VARCHAR(150) NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'GTQ',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `account_number_idx` (`number` ASC),
  INDEX `profile_account_fk` (`profile_id` ASC),
  CONSTRAINT `profile_account_fk`
    FOREIGN KEY (`profile_id`)
    REFERENCES `transactions`.`profile` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`beneficiary`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`beneficiary` (
  `id` INT(11) UNSIGNED NOT NULL,
  `account_id` INT(11) UNSIGNED NOT NULL,
  `alias` VARCHAR(50) NOT NULL,
  `max_amount_transfer` DOUBLE NOT NULL,
  `transactions_quota` SMALLINT(6) NOT NULL,
  `created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `beneficiary_alias_idx` (`alias` ASC),
  INDEX `account_beneficiary_fk` (`account_id` ASC),
  CONSTRAINT `account_beneficiary_fk`
    FOREIGN KEY (`account_id`)
    REFERENCES `transactions`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`deposit_requirement`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`deposit_requirement` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` INT(11) UNSIGNED NOT NULL,
  `requested_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` DOUBLE NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'GTQ',
  `status` CHAR(1) NOT NULL COMMENT 'R: Requested\nA: Accepted\nC: Cancelled\nN: Negated',
  `resolution_reason` VARCHAR(150) NOT NULL,
  `resolution_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `deposit_requirement_resolution_date_idx` (`resolution_date` ASC),
  INDEX `deposit_requirement_status_idx` (`status` ASC),
  INDEX `deposit_requirement_resolution_idx` (`requested_date` ASC, `status` ASC, `resolution_reason` ASC),
  INDEX `fk_deposit_requirement_account1_idx` (`account_id` ASC),
  CONSTRAINT `fk_deposit_requirement_account1`
    FOREIGN KEY (`account_id`)
    REFERENCES `transactions`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`schema_version`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`schema_version` (
  `installed_rank` INT(11) NOT NULL,
  `version` VARCHAR(50) NULL DEFAULT NULL,
  `description` VARCHAR(200) NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `script` VARCHAR(1000) NOT NULL,
  `checksum` INT(11) NULL DEFAULT NULL,
  `installed_by` VARCHAR(100) NOT NULL,
  `installed_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `execution_time` INT(11) NOT NULL,
  `success` TINYINT(1) NOT NULL,
  PRIMARY KEY (`installed_rank`),
  INDEX `schema_version_s_idx` (`success` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `transactions`.`transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions`.`transaction` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` INT(11) UNSIGNED NOT NULL,
  `transaction_type` VARCHAR(50) NOT NULL,
  `credit` SMALLINT(6) NOT NULL DEFAULT '0',
  `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` VARCHAR(150) NOT NULL,
  `amount` DOUBLE NOT NULL,
  `currency` CHAR(3) NOT NULL DEFAULT 'GTQ',
  PRIMARY KEY (`id`),
  INDEX `transaction_credit_idx` (`credit` ASC),
  INDEX `transaction_date_idx` (`date` ASC),
  INDEX `account_transaction_fk` (`account_id` ASC),
  CONSTRAINT `account_transaction_fk`
    FOREIGN KEY (`account_id`)
    REFERENCES `transactions`.`account` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
