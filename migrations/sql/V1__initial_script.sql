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

CREATE TABLE transaction_type (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(100) NOT NULL,
                description VARCHAR(200) NOT NULL,
                active SMALLINT DEFAULT 1 NOT NULL,
                PRIMARY KEY (id)
);


CREATE INDEX transaction_type_active_idx
 ON transaction_type
 ( active ASC );

CREATE TABLE role (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(50) NOT NULL,
                active SMALLINT DEFAULT 1 NOT NULL,
                PRIMARY KEY (id)
);


CREATE UNIQUE INDEX role_name_idx
 ON role
 ( name ASC );

CREATE INDEX role_active_idx
 ON role
 ( active ASC );

CREATE TABLE user (
                id INT AUTO_INCREMENT NOT NULL,
                user CHAR(50) NOT NULL,
                password CHAR NOT NULL,
                PRIMARY KEY (id)
);


CREATE UNIQUE INDEX user_usr_idx
 ON user
 ( user ASC );

CREATE INDEX user_login_idx
 ON user
 ( user ASC, password ASC );

CREATE TABLE profile (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT NOT NULL,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(150) NOT NULL,
                phone VARCHAR(30) NOT NULL,
                active SMALLINT DEFAULT 1 NOT NULL,
                PRIMARY KEY (id)
);


CREATE INDEX profile_active_idx
 ON profile
 ( active ASC );

CREATE TABLE deposit_requirement (
                id INT AUTO_INCREMENT NOT NULL,
                profile_id INT NOT NULL,
                requested_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                amount DOUBLE PRECISION NOT NULL,
                currency CHAR(3) DEFAULT 'GTQ' NOT NULL,
                status CHAR(1) DEFAULT 'R' NOT NULL,
                resolution_reason VARCHAR(150) NOT NULL,
                resolution_date DATETIME NOT NULL,
                PRIMARY KEY (id)
);

ALTER TABLE deposit_requirement MODIFY COLUMN status CHAR(1) COMMENT 'R: Requested
A: Accepted
C: Cancelled
N: Negated';


CREATE INDEX deposit_requirement_resolution_date_idx
 ON deposit_requirement
 ( resolution_date ASC );

CREATE INDEX deposit_requirement_status_idx
 ON deposit_requirement
 ( status ASC );

CREATE INDEX deposit_requirement_resolution_idx
 ON deposit_requirement
 ( requested_date, status, resolution_reason );

CREATE TABLE account (
                id INT AUTO_INCREMENT NOT NULL,
                profile_id INT NOT NULL,
                number VARCHAR(150) NOT NULL,
                currency CHAR(3) DEFAULT 'GTQ' NOT NULL,
                PRIMARY KEY (id)
);


CREATE UNIQUE INDEX account_number_idx
 ON account
 ( number ASC );

CREATE TABLE beneficiary (
                id INT NOT NULL,
                account_id INT NOT NULL,
                alias VARCHAR(50) NOT NULL,
                max_amount_transfer DOUBLE PRECISION NOT NULL,
                transactions_quota SMALLINT NOT NULL,
                created DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY (id)
);


CREATE INDEX beneficiary_alias_idx
 ON beneficiary
 ( alias ASC );

CREATE TABLE transaction (
                id INT AUTO_INCREMENT NOT NULL,
                transaction_type_id INT NOT NULL,
                account_id INT NOT NULL,
                credit SMALLINT DEFAULT 0 NOT NULL,
                date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
                DESCRIPTION VARCHAR(150) NOT NULL,
                amount DOUBLE PRECISION NOT NULL,
                currency CHAR(3) DEFAULT 'GTQ' NOT NULL,
                PRIMARY KEY (id)
);


CREATE INDEX transaction_credit_idx
 ON transaction
 ( credit ASC );

CREATE INDEX transaction_date_idx
 ON transaction
 ( date ASC );

CREATE TABLE user_role (
                user_id INT NOT NULL,
                role_id INT NOT NULL,
                PRIMARY KEY (user_id, role_id)
);


ALTER TABLE transaction ADD CONSTRAINT transaction_type_transaction_fk
FOREIGN KEY (transaction_type_id)
REFERENCES transaction_type (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE user_role ADD CONSTRAINT role_user_role_fk
FOREIGN KEY (role_id)
REFERENCES role (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE user_role ADD CONSTRAINT user_user_role_fk
FOREIGN KEY (user_id)
REFERENCES user (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE profile ADD CONSTRAINT user_profile_fk
FOREIGN KEY (user_id)
REFERENCES user (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE account ADD CONSTRAINT profile_account_fk
FOREIGN KEY (profile_id)
REFERENCES profile (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE deposit_requirement ADD CONSTRAINT profile_deposit_requirement_fk
FOREIGN KEY (profile_id)
REFERENCES profile (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE transaction ADD CONSTRAINT account_transaction_fk
FOREIGN KEY (account_id)
REFERENCES account (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;

ALTER TABLE beneficiary ADD CONSTRAINT account_beneficiary_fk
FOREIGN KEY (account_id)
REFERENCES account (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION;