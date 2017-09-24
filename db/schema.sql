-- MySQL Script generated by MySQL Workbench
-- dim 24 sep 2017 14:38:13 CEST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

-- -----------------------------------------------------
-- Schema pcea
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS pcea ;

-- -----------------------------------------------------
-- Schema pcea
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS pcea DEFAULT CHARACTER SET utf8 ;
USE pcea ;

-- -----------------------------------------------------
-- Table pcea.users
-- -----------------------------------------------------
DROP TABLE IF EXISTS pcea.users ;

CREATE TABLE IF NOT EXISTS pcea.users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(45) NOT NULL,
  password VARCHAR(45) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB
DEFAULT CHARACTER SET = big5;


-- -----------------------------------------------------
-- Table pcea.events
-- -----------------------------------------------------
DROP TABLE IF EXISTS pcea.events ;

CREATE TABLE IF NOT EXISTS pcea.events (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  currency VARCHAR(45) NOT NULL,
  PRIMARY KEY (id))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table pcea.spents
-- -----------------------------------------------------
DROP TABLE IF EXISTS pcea.spents ;

CREATE TABLE IF NOT EXISTS pcea.spents (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(45) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  insert_date TIMESTAMP NOT NULL,
  buyer INT UNSIGNED NOT NULL,
  events_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (id),
  INDEX fk_buyer (buyer ASC),
  INDEX fk_spents_events_idx (events_id ASC),
  CONSTRAINT fk_buyer
    FOREIGN KEY (buyer)
    REFERENCES pcea.users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_spents_events
    FOREIGN KEY (events_id)
    REFERENCES pcea.events (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table pcea.users_has_spents
-- -----------------------------------------------------
DROP TABLE IF EXISTS pcea.users_has_spents ;

CREATE TABLE IF NOT EXISTS pcea.users_has_spents (
  users_id INT UNSIGNED NOT NULL,
  spents_id INT UNSIGNED NOT NULL,
  user_weight INT UNSIGNED NOT NULL DEFAULT 1,
  PRIMARY KEY (users_id, spents_id),
  INDEX fk_users_has_spents_spents_idx (spents_id ASC),
  CONSTRAINT fk_users_has_spents_users
    FOREIGN KEY (users_id)
    REFERENCES pcea.users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_users_has_spents_spents
    FOREIGN KEY (spents_id)
    REFERENCES pcea.spents (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = big5;


-- -----------------------------------------------------
-- Table pcea.users_has_events
-- -----------------------------------------------------
DROP TABLE IF EXISTS pcea.users_has_events ;

CREATE TABLE IF NOT EXISTS pcea.users_has_events (
  users_id INT UNSIGNED NOT NULL,
  events_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (users_id, events_id),
  INDEX fk_users_has_events_events_idx (events_id ASC),
  INDEX fk_users_has_events_users_idx (users_id ASC),
  CONSTRAINT fk_users_has_events_users
    FOREIGN KEY (users_id)
    REFERENCES pcea.users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT fk_users_has_events_events
    FOREIGN KEY (events_id)
    REFERENCES pcea.events (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = big5;
