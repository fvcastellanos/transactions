ALTER TABLE `transactions`.`deposit_requirement`
CHANGE COLUMN `resolution_reason` `resolution_reason` VARCHAR(150) NULL ;

ALTER TABLE `transactions`.`deposit_requirement`
CHANGE COLUMN `resolution_date` `resolution_date` DATETIME NULL ;
