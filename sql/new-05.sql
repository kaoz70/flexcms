ALTER TABLE `faq`
ADD COLUMN `paginaId` INT NOT NULL AFTER `faqId`,
ADD INDEX `fk_fpaginaId_faq_idx` (`paginaId` ASC);
ALTER TABLE `faq`
ADD CONSTRAINT `fk_fpaginaId_faq`
  FOREIGN KEY (`paginaId`)
  REFERENCES `paginas` (`id`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;