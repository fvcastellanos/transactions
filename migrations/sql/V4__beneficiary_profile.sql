ALTER TABLE `transactions`.`beneficiary`
ADD COLUMN `profile_id` INT UNSIGNED NOT NULL AFTER `created`,
ADD UNIQUE INDEX `uq_account_profile` (`account_id` ASC, `profile_id` ASC);
