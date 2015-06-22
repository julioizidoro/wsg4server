-- MySQL Script generated by MySQL Workbench
-- 06/21/15 14:53:11
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema wsg4
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema wsg4
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `wsg4` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `wsg4` ;

-- -----------------------------------------------------
-- Table `wsg4`.`corrida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wsg4`.`corrida` (
  `idcorrida` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NULL,
  `descricao` VARCHAR(200) NULL,
  `data` DATE NULL,
  `cidade` VARCHAR(50) NULL,
  `estado` VARCHAR(2) NULL,
  `valorinscricao` FLOAT NULL DEFAULT 0,
  `status` VARCHAR(10) NULL,
  PRIMARY KEY (`idcorrida`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wsg4`.`corredor`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wsg4`.`corredor` (
  `idcorredor` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(10) NULL,
  `datanascimento` DATE NULL,
  `cidade` VARCHAR(50) NULL,
  `estado` VARCHAR(2) NULL,
  `status` VARCHAR(15) NULL,
  PRIMARY KEY (`idcorredor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wsg4`.`inscricao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `wsg4`.`inscricao` (
  `idinscricao` INT NOT NULL AUTO_INCREMENT,
  `statuspagamento` TINYINT(1) NULL,
  `tempo` DOUBLE NULL,
  `posicao` INT NULL,
  `corrida_idcorrida` INT NOT NULL,
  `corredor_idcorredor` INT NOT NULL,
  PRIMARY KEY (`idinscricao`),
  INDEX `fk_inscricao_corrida_idx` (`corrida_idcorrida` ASC),
  INDEX `fk_inscricao_corredor1_idx` (`corredor_idcorredor` ASC),
  CONSTRAINT `fk_inscricao_corrida`
    FOREIGN KEY (`corrida_idcorrida`)
    REFERENCES `wsg4`.`corrida` (`idcorrida`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_inscricao_corredor1`
    FOREIGN KEY (`corredor_idcorredor`)
    REFERENCES `wsg4`.`corredor` (`idcorredor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
