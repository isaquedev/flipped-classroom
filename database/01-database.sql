-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema flipped_classsroom_edu_system
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema flipped_classsroom_edu_system
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `heroku_1bb70b6ea823f68` DEFAULT CHARACTER SET utf8 ;
USE `heroku_1bb70b6ea823f68` ;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `login` VARCHAR(50) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `type` TINYINT(1) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `schoolclasses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `schoolclasses` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `id_teacher` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_schoolclasses_users_idx` (`id_teacher` ASC),
  CONSTRAINT `fk_schoolclasses_users`
    FOREIGN KEY (`id_teacher`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users_schoolclasses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users_schoolclasses` (
  `id_student` INT UNSIGNED NOT NULL,
  `id_schoolclasses` INT UNSIGNED NOT NULL,
  INDEX `fk_users_schoolclasses_users_idx` (`id_student` ASC),
  INDEX `fk_users_schoolclasses_schoolclasses_idx` (`id_schoolclasses` ASC),  
  CONSTRAINT `fk_users_schoolclasses_users`
    FOREIGN KEY (`id_student`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_schoolclasses_schoolclasses`
    FOREIGN KEY (`id_schoolclasses`)
    REFERENCES `schoolclasses` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `questionnaires`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `questionnaires` (
  `id`INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `id_teacher` INT UNSIGNED NOT NULL,
  `is_public` TINYINT(1) NOT NULL,
  `is_test` TINYINT(1) NOT NULL,
  `duration` INT NOT NULL,
  `random_answers` TINYINT(1) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
INDEX `fk_questionnaire_users_idx` (`id_teacher` ASC),
  CONSTRAINT `fk_questionnaire_users`
    FOREIGN KEY (`id_teacher`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `questions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `questions` (
  `id`INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `enunciation` TEXT NOT NULL,
  `correct_answer` TEXT NOT NULL,
  `incorrect_answer1` TEXT NOT NULL,
  `incorrect_answer2` TEXT NOT NULL,
  `incorrect_answer3` TEXT NOT NULL,
  `incorrect_answer4` TEXT NOT NULL,
  `id_questionnaire` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
INDEX `fk_questions_questionnaires_idx` (`id_questionnaire` ASC),
  CONSTRAINT `fk_questions_questionnaires`
    FOREIGN KEY (`id_questionnaire`)
    REFERENCES `questionnaires` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `users_questionnaire`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users_questionnaires` (
  `id_student` INT UNSIGNED NOT NULL,
  `id_questionnaire` INT UNSIGNED NOT NULL,
  `is_done` TINYINT(1) NOT NULL,
  `grades` INT NOT NULL,
  INDEX `fk_users_questionnaires_users_idx` (`id_student` ASC),
  INDEX `fk_users_questionnaires_questionnaires_idx` (`id_questionnaire` ASC),  
  CONSTRAINT `fk_users_questionnaires_users`
    FOREIGN KEY (`id_student`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_questionnaires_questionnaires`
    FOREIGN KEY (`id_questionnaire`)
    REFERENCES `questionnaires` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `lessons`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(50) NOT NULL,
  `release_date` DATETIME NOT NULL,
  `id_schoolclasses` INT UNSIGNED NOT NULL,
  `text_content` TEXT,
  `video` VARCHAR(100),
  `id_questionnaire` INT UNSIGNED,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lessons_schoolclasses_idx` (`id_schoolclasses` ASC),
  INDEX `fk_lessons_questionnaire_idx` (`id_questionnaire` ASC),
  CONSTRAINT `fk_lessons_schoolclasses`
    FOREIGN KEY (`id_schoolclasses`)
    REFERENCES `schoolclasses` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lessons_questionnaire`
    FOREIGN KEY (`id_questionnaire`)
    REFERENCES `questionnaires` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;