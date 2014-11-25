SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `qmdb` DEFAULT CHARACTER SET utf8 ;
USE `qmdb` ;

-- -----------------------------------------------------
-- Table `qmdb`.`online_review_site`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`online_review_site` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`online_review_site` (
  `idOnlineReviewSite` INT NOT NULL AUTO_INCREMENT ,
  `siteName` VARCHAR(45) NULL ,
  `createdAtTimestamp` INT NULL ,
  `updatedAtTimestamp` INT NULL ,
  PRIMARY KEY (`idOnlineReviewSite`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`user` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`user` (
  `idUser` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  `passwordMD5` BIT(128) NULL ,
  `createdAtTimestamp` INT NULL ,
  `updatedAtTimestamp` INT NULL ,
  `email` VARCHAR(45) NULL ,
  PRIMARY KEY (`idUser`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`authentication_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`authentication_data` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`authentication_data` (
  `idAuthenticationData` INT NOT NULL ,
  `logsIntoOnlineReviewSiteId` VARCHAR(45) NULL ,
  `registeredByUserId` INT NOT NULL ,
  `createdAtTimestamp` INT NULL ,
  `updatedAtTimestamp` INT NULL ,
  PRIMARY KEY (`idAuthenticationData`) ,
  INDEX `fk_authentication_data_1` () ,
  INDEX `fk_authentication_data_2` () ,
  INDEX `fk_authentication_data_3` (`registeredByUserId` ASC) ,
  CONSTRAINT `fk_authentication_data_2`
    FOREIGN KEY ()
    REFERENCES `qmdb`.`online_review_site` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_authentication_data_3`
    FOREIGN KEY (`registeredByUserId` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`user_client`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`user_client` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`user_client` (
  `idUser` INT NOT NULL ,
  PRIMARY KEY (`idUser`) ,
  INDEX `fk_client_user_1` (`idUser` ASC) ,
  CONSTRAINT `fk_client_user_1`
    FOREIGN KEY (`idUser` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`client_business_branch`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`client_business_branch` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`client_business_branch` (
  `idClientBusinessBranch` INT NOT NULL ,
  `belongsToUserClientId` INT NOT NULL ,
  `branchName` VARCHAR(200) NULL ,
  PRIMARY KEY (`idClientBusinessBranch`) ,
  INDEX `fk_client_business_branch_1` (`belongsToUserClientId` ASC) ,
  CONSTRAINT `fk_client_business_branch_1`
    FOREIGN KEY (`belongsToUserClientId` )
    REFERENCES `qmdb`.`user_client` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`search_parameters`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`search_parameters` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`search_parameters` (
  `idSearchParameters` INT NOT NULL AUTO_INCREMENT ,
  `relevantToClientBranchId` INT NOT NULL ,
  `containsAuthDataId` INT NULL ,
  `definedByUserId` INT NOT NULL ,
  `createdAtTimestamp` INT NULL ,
  `updatedAtTimestamp` INT NULL ,
  PRIMARY KEY (`idSearchParameters`) ,
  INDEX `fk_search_parameters_1` (`containsAuthDataId` ASC) ,
  INDEX `fk_search_parameters_2` (`definedByUserId` ASC) ,
  INDEX `fk_search_parameters_3` (`relevantToClientBranchId` ASC) ,
  CONSTRAINT `fk_search_parameters_1`
    FOREIGN KEY (`containsAuthDataId` )
    REFERENCES `qmdb`.`authentication_data` (`idAuthenticationData` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_search_parameters_2`
    FOREIGN KEY (`definedByUserId` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_search_parameters_3`
    FOREIGN KEY (`relevantToClientBranchId` )
    REFERENCES `qmdb`.`client_business_branch` (`idClientBusinessBranch` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `qmdb`.`search_parameters_yelp_reviews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`search_parameters_yelp_reviews` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`search_parameters_yelp_reviews` (
  `idSearchParameters` INT NOT NULL ,
  `yelpBusinesssName` VARCHAR(45) NOT NULL ,
  `yelpBusinessLocation` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`idSearchParameters`) ,
  INDEX `fk_search_parameters_yelp_reviews_1` (`idSearchParameters` ASC) ,
  CONSTRAINT `fk_search_parameters_yelp_reviews_1`
    FOREIGN KEY (`idSearchParameters` )
    REFERENCES `qmdb`.`search_parameters` (`idSearchParameters` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Child table of search_parameters';


-- -----------------------------------------------------
-- Table `qmdb`.`post`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`post` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`post` (
  `idPost` INT NOT NULL AUTO_INCREMENT ,
  `isReplyToPostId` INT NULL ,
  `createdByUserId` INT NULL ,
  `publishedAtTimestamp` INT NOT NULL ,
  `updatedAtTimestamp` INT NULL ,
  `foundOnReviewSiteId` INT NULL ,
  PRIMARY KEY (`idPost`) ,
  INDEX `fk_post_1` (`idPost` ASC) ,
  INDEX `fk_post_2` (`createdByUserId` ASC) ,
  INDEX `fk_post_3` (`foundOnReviewSiteId` ASC) ,
  CONSTRAINT `fk_post_1`
    FOREIGN KEY (`idPost` )
    REFERENCES `qmdb`.`post` (`isReplyToPostId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_2`
    FOREIGN KEY (`createdByUserId` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_3`
    FOREIGN KEY (`foundOnReviewSiteId` )
    REFERENCES `qmdb`.`online_review_site` (`idOnlineReviewSite` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`post_review`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`post_review` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`post_review` (
  `idPost` INT NOT NULL AUTO_INCREMENT ,
  `hasRating` TINYINT NOT NULL COMMENT '1-100 integer' ,
  `metadata` TEXT NULL ,
  `scrapedAtTimestamp` INT NULL ,
  `hasURL` VARCHAR(200) NULL ,
  `publishedByAuthorURL` VARCHAR(200) NULL ,
  `hasPostContents` TEXT NULL ,
  `matchesSearchParametersId` INT NOT NULL ,
  `handledByCustomerServiceAgentUserId` INT NULL ,
  PRIMARY KEY (`idPost`) ,
  INDEX `fk_post_review_1` (`matchesSearchParametersId` ASC) ,
  INDEX `fk_post_review_2` (`idPost` ASC) ,
  CONSTRAINT `fk_post_review_1`
    FOREIGN KEY (`matchesSearchParametersId` )
    REFERENCES `qmdb`.`search_parameters` (`idSearchParameters` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_review_2`
    FOREIGN KEY (`idPost` )
    REFERENCES `qmdb`.`post` (`idPost` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Child table of post, a Review is a Post';


-- -----------------------------------------------------
-- Table `qmdb`.`post_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`post_message` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`post_message` (
  `idPost` INT NULL ,
  `userName` VARCHAR(100) NULL ,
  `userImageUrl` VARCHAR(255) NULL ,
  `subject` VARCHAR(45) NULL ,
  `content` TEXT NULL ,
  `hasURL` VARCHAR(200) NULL ,
  PRIMARY KEY (`idPost`) ,
  INDEX `fk_post_message_1` (`idPost` ASC) ,
  CONSTRAINT `fk_post_message_1`
    FOREIGN KEY (`idPost` )
    REFERENCES `qmdb`.`post` (`idPost` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`search_parameters_yelp_messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`search_parameters_yelp_messages` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`search_parameters_yelp_messages` (
  `idSearchParameters` INT NOT NULL ,
  PRIMARY KEY (`idSearchParameters`) ,
  INDEX `fk_search_parameters_yelp_messages_1` (`idSearchParameters` ASC) ,
  CONSTRAINT `fk_search_parameters_yelp_messages_1`
    FOREIGN KEY (`idSearchParameters` )
    REFERENCES `qmdb`.`search_parameters` (`idSearchParameters` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`user_customer_service_agent`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`user_customer_service_agent` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`user_customer_service_agent` (
  `idUser` INT NOT NULL ,
  PRIMARY KEY (`idUser`) ,
  INDEX `fk_user_customer_service_agent_1` (`idUser` ASC) ,
  CONSTRAINT `fk_user_customer_service_agent_1`
    FOREIGN KEY (`idUser` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`user_admin`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`user_admin` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`user_admin` (
  `idUser` INT NOT NULL ,
  PRIMARY KEY (`idUser`) ,
  INDEX `fk_user_admin_1` (`idUser` ASC) ,
  CONSTRAINT `fk_user_admin_1`
    FOREIGN KEY (`idUser` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`user_bookkeeper`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`user_bookkeeper` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`user_bookkeeper` (
  `idUser` INT NOT NULL ,
  PRIMARY KEY (`idUser`) ,
  INDEX `fk_user_bookkeeper_1` (`idUser` ASC) ,
  CONSTRAINT `fk_user_bookkeeper_1`
    FOREIGN KEY (`idUser` )
    REFERENCES `qmdb`.`user` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `qmdb`.`works_with`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `qmdb`.`works_with` ;

CREATE  TABLE IF NOT EXISTS `qmdb`.`works_with` (
  `user_customer_service_agent_idUser` INT NOT NULL ,
  `user_client_idUser` INT NOT NULL ,
  PRIMARY KEY (`user_customer_service_agent_idUser`, `user_client_idUser`) ,
  INDEX `fk_user_customer_service_agent_has_user_client_user_client1` (`user_client_idUser` ASC) ,
  INDEX `fk_user_customer_service_agent_has_user_client_user_customer_1` (`user_customer_service_agent_idUser` ASC) ,
  CONSTRAINT `fk_user_customer_service_agent_has_user_client_user_customer_1`
    FOREIGN KEY (`user_customer_service_agent_idUser` )
    REFERENCES `qmdb`.`user_customer_service_agent` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_customer_service_agent_has_user_client_user_client1`
    FOREIGN KEY (`user_client_idUser` )
    REFERENCES `qmdb`.`user_client` (`idUser` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
