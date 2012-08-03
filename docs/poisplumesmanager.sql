SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `poisplumesmanager` ;
CREATE SCHEMA IF NOT EXISTS `poisplumesmanager` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `poisplumesmanager` ;

-- -----------------------------------------------------
-- Table `poisplumesmanager`.`category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`category` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`category` (
  `ref` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `desc` TEXT NULL ,
  `category_ref` VARCHAR(45) NULL ,
  PRIMARY KEY (`ref`) ,
  CONSTRAINT `fk_category_category1`
    FOREIGN KEY (`category_ref` )
    REFERENCES `poisplumesmanager`.`category` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `poisplumesmanager`.`category` (`name` ASC) ;

CREATE INDEX `fk_category_category1` ON `poisplumesmanager`.`category` (`category_ref` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`tax`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`tax` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`tax` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `ratio` DECIMAL(10,2) NOT NULL ,
  `name` VARCHAR(45) NOT NULL ,
  `description` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `poisplumesmanager`.`tax` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`article` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`article` (
  `ref` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  `description` TEXT NULL ,
  `tax_id` INT NOT NULL ,
  `priceht` DECIMAL(10,2) NOT NULL ,
  `stocked` TINYINT(1) NOT NULL ,
  `qty` DECIMAL NULL ,
  `unit` VARCHAR(45) NULL ,
  PRIMARY KEY (`ref`) ,
  CONSTRAINT `fk_article_tva1`
    FOREIGN KEY (`tax_id` )
    REFERENCES `poisplumesmanager`.`tax` (`id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `poisplumesmanager`.`article` (`name` ASC) ;

CREATE INDEX `fk_article_tva1` ON `poisplumesmanager`.`article` (`tax_id` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`provider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`provider` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`provider` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(255) NOT NULL ,
  `info` LONGTEXT NULL ,
  `comment` LONGTEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `poisplumesmanager`.`provider` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`categoryarticle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`categoryarticle` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`categoryarticle` (
  `article_ref` VARCHAR(45) NOT NULL ,
  `category_ref` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`article_ref`, `category_ref`) ,
  CONSTRAINT `fk_categoryarticle_article1`
    FOREIGN KEY (`article_ref` )
    REFERENCES `poisplumesmanager`.`article` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_categoryarticle_category1`
    FOREIGN KEY (`category_ref` )
    REFERENCES `poisplumesmanager`.`category` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_categoryarticle_article1` ON `poisplumesmanager`.`categoryarticle` (`article_ref` ASC) ;

CREATE INDEX `fk_categoryarticle_category1` ON `poisplumesmanager`.`categoryarticle` (`category_ref` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`stocktrail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`stocktrail` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`stocktrail` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `articleref` VARCHAR(45) NOT NULL ,
  `articlename` VARCHAR(255) NOT NULL ,
  `previous` DECIMAL NOT NULL ,
  `modif` DECIMAL NOT NULL ,
  `new` DECIMAL NOT NULL ,
  `unit` VARCHAR(45) NULL ,
  `date` DATETIME NOT NULL ,
  `user` VARCHAR(45) NOT NULL ,
  `comment` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`articleprovider`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`articleprovider` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`articleprovider` (
  `provider_id` INT NOT NULL ,
  `article_ref` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`provider_id`, `article_ref`) ,
  CONSTRAINT `fk_articleprovider_provider1`
    FOREIGN KEY (`provider_id` )
    REFERENCES `poisplumesmanager`.`provider` (`id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_articleprovider_article1`
    FOREIGN KEY (`article_ref` )
    REFERENCES `poisplumesmanager`.`article` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_articleprovider_article1` ON `poisplumesmanager`.`articleprovider` (`article_ref` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`payment` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`payment` (
  `ref` VARCHAR(45) NOT NULL ,
  `name` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`ref`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`operations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`operations` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`operations` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `date` DATETIME NOT NULL ,
  `payment_ref` VARCHAR(45) NOT NULL ,
  `total` DECIMAL NOT NULL ,
  `comment` TEXT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_operations_payement1`
    FOREIGN KEY (`payment_ref` )
    REFERENCES `poisplumesmanager`.`payment` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_operations_payement1` ON `poisplumesmanager`.`operations` (`payment_ref` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`operationstrail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`operationstrail` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`operationstrail` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `priceht` DECIMAL NOT NULL ,
  `tva_ratio` DECIMAL NOT NULL ,
  `operations_id` INT NOT NULL ,
  `article_ref` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_operationstrail_operations1`
    FOREIGN KEY (`operations_id` )
    REFERENCES `poisplumesmanager`.`operations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_operationstrail_article1`
    FOREIGN KEY (`article_ref` )
    REFERENCES `poisplumesmanager`.`article` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_operationstrail_operations1` ON `poisplumesmanager`.`operationstrail` (`operations_id` ASC) ;

CREATE INDEX `fk_operationstrail_article1` ON `poisplumesmanager`.`operationstrail` (`article_ref` ASC) ;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`combo`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`combo` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`combo` (
  `ref` VARCHAR(45) NOT NULL ,
  `indexes` INT NOT NULL ,
  PRIMARY KEY (`ref`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `poisplumesmanager`.`comboarticle`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `poisplumesmanager`.`comboarticle` ;

CREATE  TABLE IF NOT EXISTS `poisplumesmanager`.`comboarticle` (
  `combo_ref` VARCHAR(45) NOT NULL ,
  `article_ref` VARCHAR(45) NOT NULL ,
  `index` INT NOT NULL ,
  PRIMARY KEY (`combo_ref`, `article_ref`) ,
  CONSTRAINT `fk_comboarticle_combo1`
    FOREIGN KEY (`combo_ref` )
    REFERENCES `poisplumesmanager`.`combo` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT,
  CONSTRAINT `fk_comboarticle_article1`
    FOREIGN KEY (`article_ref` )
    REFERENCES `poisplumesmanager`.`article` (`ref` )
    ON DELETE RESTRICT
    ON UPDATE RESTRICT)
ENGINE = InnoDB;

CREATE INDEX `fk_comboarticle_article1` ON `poisplumesmanager`.`comboarticle` (`article_ref` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
