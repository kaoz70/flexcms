ALTER TABLE `images`
RENAME TO  `images_config` ;

ALTER TABLE `images`
ADD COLUMN `created_at` TIMESTAMP NULL AFTER `crop`,
ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`,
ADD COLUMN `deleted_at` TIMESTAMP NULL AFTER `updated_at`, RENAME TO  `images_config` ;

ALTER TABLE `images_config`
ADD CONSTRAINT `fk_images_config_category_id`
  FOREIGN KEY (`category_id`)
  REFERENCES `categories` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `images_config`
CHANGE COLUMN `id` `id` INT(11) NOT NULL ,
ADD COLUMN `force_jpg` TINYINT(1) NULL AFTER `crop`,
ADD COLUMN `background_color` VARCHAR(45) NULL AFTER `force_jpg`,
ADD COLUMN `quality` DECIMAL(3,0) NULL AFTER `background_color`,
ADD COLUMN `restrict_proportions` TINYINT(1) NULL AFTER `quality`,
ADD COLUMN `watermark_file_id` INT(11) NULL AFTER `restrict_proportions`,
ADD COLUMN `watermark_position` VARCHAR(45) NULL AFTER `watermark_file_id`,
ADD COLUMN `watermark_alpha` DECIMAL(3,0) NULL AFTER `watermark_position`,
ADD COLUMN `watermark_repeat` TINYINT(1) NULL AFTER `watermark_alpha`;

ALTER TABLE `images_config`
ADD COLUMN `watermark` TINYINT(1) NULL AFTER `restrict_proportions`;

ALTER TABLE `files`
ADD COLUMN `created_at` TIMESTAMP NULL AFTER `type`,
ADD COLUMN `updated_at` TIMESTAMP NULL AFTER `created_at`,
ADD COLUMN `deleted_at` TIMESTAMP NULL AFTER `updated_at`;

ALTER TABLE `files`
ADD COLUMN `mime_type` VARCHAR(45) NULL AFTER `type`,
ADD COLUMN `file_ext` VARCHAR(45) NULL AFTER `mime_type`,
ADD COLUMN `raw_name` VARCHAR(45) NULL AFTER `file_ext`;

ALTER TABLE `images_config`
CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;

ALTER TABLE `files`
CHANGE COLUMN `type` `type` VARCHAR(10) NULL DEFAULT NULL ;

ALTER TABLE `image_sections`
CHANGE COLUMN `section_id` `section` VARCHAR(45) NOT NULL ;