ALTER TABLE `transactions`.`transaction`
ADD COLUMN `other_account_id` INT UNSIGNED NULL AFTER `account_id`,
ADD INDEX `transaction_otherr_account_id` (`other_account_id` ASC);
