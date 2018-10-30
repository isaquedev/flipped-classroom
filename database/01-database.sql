-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema pp_project_manager
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema pp_project_manager
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `pp_project_manager` DEFAULT CHARACTER SET utf8 ;
USE `pp_project_manager` ;

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `login` VARCHAR(250) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `user_type` VARCHAR(1) NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `turmas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `id_professor` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_turmas_users_idx` (`id_professor` ASC),
  CONSTRAINT `fk_turmas_users`
    FOREIGN KEY (`id_professor`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `users_turmas
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users_turmas` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_aluno` INT UNSIGNED NOT NULL,
  `id_turma` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_users_turmas_users_idx` (`id_aluno` ASC),
  INDEX `fk_users_turmas_turmas_idx` (`id_turma` ASC),  
  CONSTRAINT `fk_users_turmas_users`
    FOREIGN KEY (`id_aluno`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_turmas_turmas`
    FOREIGN KEY (`id_turma`)
    REFERENCES `projects` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `aulas`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sections` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `description` TEXT NULL,
  `turma_id` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_sections_turmas_idx` (`turma_id` ASC),
  CONSTRAINT `fk_sections_turmas`
    FOREIGN KEY (`turma_id`)
    REFERENCES `projects` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `conteudo_aula`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `description` VARCHAR(45) NULL,
  `done` TINYINT NOT NULL DEFAULT 0,
  `due_date` DATETIME NULL,
  `assigned_to` INT UNSIGNED NOT NULL,
  `turma_id` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tasks_sections1_idx` (`turma_id` ASC),
  INDEX `fk_tasks_users_turmas1_idx` (`assigned_to` ASC),
  CONSTRAINT `fk_tasks_sections1`
    FOREIGN KEY (`turma_id`)
    REFERENCES `sections` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tasks_users_turmas1`
    FOREIGN KEY (`assigned_to`)
    REFERENCES `users_turmas` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `subtasks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `subtasks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NULL,
  `done` TINYINT NULL DEFAULT 0,
  `task_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_subtasks_tasks1_idx` (`task_id` ASC),
  CONSTRAINT `fk_subtasks_tasks1`
    FOREIGN KEY (`task_id`)
    REFERENCES `tasks` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `schedules`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` TEXT NOT NULL,
  `due_date` DATETIME NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `created` DATETIME NULL,
  `modified` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_schedules_projects1_idx` (`user_id` ASC),
  CONSTRAINT `fk_schedules_projects1`
    FOREIGN KEY (`user_id`)
    REFERENCES `projects` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
