ALTER TABLE `forms`
DROP FOREIGN KEY `fk_contact_id_forms`;
ALTER TABLE `forms`
CHANGE COLUMN `contact_id` `email` VARCHAR(45) NULL DEFAULT NULL ,
DROP INDEX `fk_contact_id_forms_idx` ;

DROP TABLE `contacts`;