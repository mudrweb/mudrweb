SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `mudr` ;
CREATE SCHEMA IF NOT EXISTS `mudr` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mudr` ;

-- -----------------------------------------------------
-- Table `mudr`.`layouts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`layouts` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`layouts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `layout` VARCHAR(45) NULL ,
  `layout_group` VARCHAR(30) NULL ,
  `layout_desc` VARCHAR(130) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`users` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `accountStatus` ENUM('active','pending','inactive','archive') NULL ,
  `username` VARCHAR(20) NULL ,
  `password` VARCHAR(45) NULL ,
  `passwordTemp` VARCHAR(50) NULL ,
  `passwordFTP` VARCHAR(50) NULL ,
  `salt` VARCHAR(20) NULL ,
  `role` ENUM('uživatel','admin') NULL ,
  `usersSponsor` INT NULL ,
  `usersSponsorIsReseller` INT NULL ,
  `usersSponsoringNumber` VARCHAR(5) NULL ,
  `usedReferralBonus` INT NULL ,
  `superUserActive` TINYINT(1)  NULL ,
  `subdomain` VARCHAR(30) NULL ,
  `dateOfRegistration` DATETIME NULL ,
  `program` VARCHAR(25) NULL ,
  `advertisement` VARCHAR(25) NULL ,
  `registrationToken` VARCHAR(45) NULL ,
  `dateOfActivation` DATETIME NULL ,
  `paymentReceived` ENUM('yes','no') NULL ,
  `dateOfPayment` DATE NULL ,
  `dateFrom` DATE NULL ,
  `dateTo` DATE NULL ,
  `passwordChanged` DATETIME NULL ,
  `lastLogin` DATETIME NULL ,
  `lastLogout` DATETIME NULL ,
  `passwordResent` DATETIME NULL ,
  `maintenanceMode` ENUM('on','off') NULL ,
  `subdomainStatus` ENUM('N/A','Valid','Invalid') NULL ,
  `realSubdomainStatus` ENUM('N/A','Valid','Invalid') NULL ,
  `notificationCounter` INT NULL ,
  `notificationDate` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`menuItems`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`menuItems` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`menuItems` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idusers` INT NOT NULL ,
  `itemId` INT NULL ,
  `itemName` VARCHAR(45) NULL ,
  `itemContent` TEXT NULL ,
  `itemPublished` ENUM('yes','no') NOT NULL ,
  `itemNameRouteCs` VARCHAR(45) NULL ,
  `lastChange` DATETIME NULL ,
  PRIMARY KEY (`id`, `idusers`) ,
  INDEX `fk_menuItem_users1` (`idusers` ASC) ,
  CONSTRAINT `fk_menuItem_users1`
    FOREIGN KEY (`idusers` )
    REFERENCES `mudr`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`users_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`users_data` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`users_data` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idusers` INT NOT NULL ,
  `name` VARCHAR(45) NULL ,
  `surname` VARCHAR(45) NULL ,
  `titleBefore` VARCHAR(12) NULL ,
  `titleAfter` VARCHAR(12) NULL ,
  `doctorGroup` VARCHAR(40) NULL ,
  `gender` ENUM('muž','žena') NULL ,
  `email` VARCHAR(50) NULL ,
  `street` VARCHAR(50) NULL ,
  `city` VARCHAR(50) NULL ,
  `zip` VARCHAR(8) NULL ,
  `region` VARCHAR(30) NULL ,
  `streetInvoice` VARCHAR(50) NULL ,
  `cityInvoice` VARCHAR(50) NULL ,
  `zipInvoice` VARCHAR(8) NULL ,
  `phone` INT NULL ,
  `ic` VARCHAR(10) NULL ,
  `dic` VARCHAR(10) NULL ,
  `addressMatch` TINYINT(1)  NULL ,
  `lastChange` DATETIME NULL ,
  PRIMARY KEY (`id`, `idusers`) ,
  INDEX `fk_users_data_users1` (`idusers` ASC) ,
  CONSTRAINT `fk_users_data_users1`
    FOREIGN KEY (`idusers` )
    REFERENCES `mudr`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`users_websiteData`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`users_websiteData` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`users_websiteData` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idusers` INT NOT NULL ,
  `layout` VARCHAR(25) NULL ,
  `layout_group` VARCHAR(25) NULL ,
  `header1` TEXT NULL ,
  `header2` TEXT NULL ,
  `title` VARCHAR(100) NULL ,
  `description` TEXT NULL ,
  `keywords` TEXT NULL ,
  `headerImage` VARCHAR(100) NULL ,
  `colourScheme` VARCHAR(45) NULL ,
  `lastChange` DATETIME NULL ,
  PRIMARY KEY (`id`, `idusers`) ,
  INDEX `fk_users_websiteData_users1` (`idusers` ASC) ,
  CONSTRAINT `fk_users_websiteData_users1`
    FOREIGN KEY (`idusers` )
    REFERENCES `mudr`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`passResendAttempts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`passResendAttempts` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`passResendAttempts` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `subdomain` VARCHAR(45) NULL ,
  `ip` VARCHAR(17) NULL ,
  `dateTime` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`guestBook_q`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`guestBook_q` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`guestBook_q` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(35) NULL ,
  `question` TEXT NULL ,
  `dateTime` DATETIME NULL ,
  `idusers` INT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`guestBook_a`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`guestBook_a` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`guestBook_a` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idguestBook_q` INT NOT NULL ,
  `answer` TEXT NULL ,
  `dateTime` DATETIME NULL ,
  PRIMARY KEY (`id`, `idguestBook_q`) ,
  INDEX `fk_guestBook_a_guestBook_q1` (`idguestBook_q` ASC) ,
  CONSTRAINT `fk_guestBook_a_guestBook_q1`
    FOREIGN KEY (`idguestBook_q` )
    REFERENCES `mudr`.`guestBook_q` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`guestBook`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`guestBook` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`guestBook` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `idusers` INT NOT NULL ,
  `guestBookUserName` VARCHAR(30) NULL ,
  `guestBookPublished` ENUM('yes','no') NULL ,
  `lastChange` DATETIME NULL ,
  PRIMARY KEY (`id`, `idusers`) ,
  INDEX `fk_questBook_users1` (`idusers` ASC) ,
  CONSTRAINT `fk_questBook_users1`
    FOREIGN KEY (`idusers` )
    REFERENCES `mudr`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`resellers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`resellers` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`resellers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fullName` VARCHAR(100) NULL ,
  `fullAddress` TEXT NULL ,
  `accountNumber` VARCHAR(45) NULL ,
  `phone` INT NULL ,
  `email` VARCHAR(50) NULL ,
  `resellersSponsoringNumber` VARCHAR(5) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mudr`.`lastSearchItems`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mudr`.`lastSearchItems` ;

CREATE  TABLE IF NOT EXISTS `mudr`.`lastSearchItems` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `searchData` TEXT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `mudr`.`layouts`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (1, 'layout_A1', 'all', 'A - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (2, 'layout_A2', 'all', 'A - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (3, 'layout_A1_specific', 'xa', 'A - Uživatelsky specifický vzhled');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (4, 'layout_A3', 'all', 'A - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (5, 'layout_A4', 'all', 'A - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (6, 'layout_A5', 'all', 'A - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (7, 'layout_A6', 'all', 'A - modra');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (8, 'layout_B1', 'all', 'B - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (9, 'layout_B2', 'all', 'B - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_B3', 'all', 'B - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_B4', 'all', 'B - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_B5', 'all', 'B - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_B6', 'all', 'B - modra');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C1', 'all', 'C - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C2', 'all', 'C - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C3', 'all', 'C - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C4', 'all', 'C - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C5', 'all', 'C - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_C6', 'all', 'C - modra');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D1', 'all', 'D - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D2', 'all', 'D - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D3', 'all', 'D - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D4', 'all', 'D - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D5', 'all', 'D - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_D6', 'all', 'D - modra');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E1', 'all', 'E - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E2', 'all', 'E - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E3', 'all', 'E - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E4', 'all', 'E - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E5', 'all', 'E - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_E6', 'all', 'E - modra');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F1', 'all', 'F - bila');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F2', 'all', 'F - cerna');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F3', 'all', 'F - zelena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F4', 'all', 'F - cervena');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F5', 'all', 'F - zluta');
INSERT INTO `mudr`.`layouts` (`id`, `layout`, `layout_group`, `layout_desc`) VALUES (NULL, 'layout_F6', 'all', 'F - modra');

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `passwordTemp`, `passwordFTP`, `salt`, `role`, `usersSponsor`, `usersSponsorIsReseller`, `usersSponsoringNumber`, `usedReferralBonus`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `paymentReceived`, `dateOfPayment`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (0, '', 'admin', 'e18290be7eb5f8be11bb4c9ed494f354f3915c4f', NULL, NULL, '@Xw^~Mr4L60o;E1n', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `passwordTemp`, `passwordFTP`, `salt`, `role`, `usersSponsor`, `usersSponsorIsReseller`, `usersSponsoringNumber`, `usedReferralBonus`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `paymentReceived`, `dateOfPayment`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (1, 'active', 'xa', '7afa699467e313cd534eed0dcd24291e6071d25a', NULL, NULL, 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, NULL, '1234', NULL, 0, 'xa', '2011-12-01 00:00:00', 'demo', 'zentiva', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', '1971-00-00', 'yes', '2011-12-02', '2011-10-01', '2012-09-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `passwordTemp`, `passwordFTP`, `salt`, `role`, `usersSponsor`, `usersSponsorIsReseller`, `usersSponsoringNumber`, `usedReferralBonus`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `paymentReceived`, `dateOfPayment`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (2, 'active', 'mskdfdj', '7afa699467e313cd534eed0dcd24291e6071d25a', NULL, NULL, 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, NULL, '5678', NULL, 0, 'tutururu', '2011-04-01 00:00:00', 'basic', 'no', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', '1971-00-00', 'no', '1971-00-00', '2012-01-17', '2012-10-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `passwordTemp`, `passwordFTP`, `salt`, `role`, `usersSponsor`, `usersSponsorIsReseller`, `usersSponsoringNumber`, `usedReferralBonus`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `paymentReceived`, `dateOfPayment`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (3, 'active', 'tester', '7afa699467e313cd534eed0dcd24291e6071d25a', NULL, NULL, 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, NULL, '1222', NULL, 0, 'tester11', '2011-12-17 00:00:00', 'premium', 'no', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', '1971-00-00', 'no', '1971-00-00', '2012-01-27', '2012-11-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `passwordTemp`, `passwordFTP`, `salt`, `role`, `usersSponsor`, `usersSponsorIsReseller`, `usersSponsoringNumber`, `usedReferralBonus`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `paymentReceived`, `dateOfPayment`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (4, 'active', 'ester1', '7afa699467e313cd534eed0dcd24291e6071d25a', NULL, NULL, 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, NULL, '1344', NULL, 0, 'mrkva321', '2011-12-01 00:00:00', 'demo', 'wallmark', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', '1971-00-00', 'no', '1971-00-00', '2012-02-04', '2012-12-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`menuItems`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (2, 1, 2, 'O nás', '<h1>Person&aacute;l</h1><p>	&nbsp;</p><p>	<em><span style=\"color:#ff0000;\">Zde můžete um&iacute;stit V&aacute;&scaron; stručn&yacute; životopis, jak rovněž životopisy Va&scaron;ich zaměstnaců.</span></em></p><p>&nbsp;</p><p><em><span style=\"color:#ff0000;\">Pro zpestřen&iacute; Va&scaron;&iacute; prezentace je vhodn&eacute; um&iacute;stit Va&scaron;i fotografii, popř. fotografie Va&scaron;&iacute;ch zaměstnanců.</span></em></p>', 'yes', 'o-nas', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (3, 1, 3, 'Služby', '<h1>Služby</h1><p>&nbsp;</p><p>	<span style=\"color:#ff0000;\"><em>Vložte popis poskytovan&yacute;ch služeb, popř&iacute;padě cen&iacute;k. Můžete popis doplnit ilustračn&iacute;mi obr&aacute;zky z na&scaron;&iacute; galerie.</em></span></p>', 'no', 'sluzby', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (4, 1, 4, 'Smluvní pojišťovny', '<h1>Smluvn&iacute; poji&scaron;ťovny</h1><p>na&scaron;e pracovi&scaron;tě je smluvn&iacute;m partnerem n&aacute;sleduj&iacute;c&iacute;h poji&scaron;ťoven:</p><p>&nbsp;</p><p><span style=\"color:#ff0000;\"><em>(nehod&iacute;c&iacute; smažte)</em></span></p>', 'yes', 'smluvni-pojistovny', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (5, 1, 5, 'Kontakt', '...', 'yes', 'kontakt', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (6, 1, 6, 'Volná položka', '...', 'no', 'volna-polozka', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (8, 1, 1, 'Úvod', '<p><span style=\"color:#ff0000;\"><em>Na prvn&iacute; str&aacute;nce je vhodn&eacute; stručn&eacute; uv&eacute;st z&aacute;kladni informace o V&aacute;s, Va&scaron;&iacute; ambulanci, poskytovan&yacute;ch služb&aacute;ch. Např.:</em></span></p><p>&nbsp;</p><h2><strong>V&iacute;tejte na na&scaron;ich str&aacute;nk&aacute;ch!</strong></h2><p>&nbsp;</p><p>Na&scaron;e ambulance poskytuje zdravotn&iacute; p&eacute;či již od roku 2000. Na&scaron;im c&iacute;lem jsou spokojen&iacute; pacienti, kter&yacute;m se vždy dostane odpovědn&eacute; p&eacute;če.</p><p>&nbsp;</p><p>Pokud m&aacute;te nějak&eacute; zdrvotn&iacute; pot&iacute;že nev&aacute;hejte n&aacute;s nav&scaron;t&iacute;vit na adrese uveden&eacute; v sekci Kontakt. Jme V&aacute;m k dispozici každ&yacute; v&scaron;edn&iacute; den od 7:00 do 16:00, viz Ordinačn&iacute; hodiny (n&iacute;že)</p><p>&nbsp;</p><p>&nbsp;</p><p><span style=\"color:#ff0000;\"><em>Můžete tak&eacute; uv&eacute;st např.</em></span></p><h1><img alt=\"\" src=\"http://mudrweb.cz/images/commonGallery/galerie/ilustracni_obrazky/3Dman/3DMUZ_004.jpg\" style=\"width: 147px; height: 252px; float: right;\" /></h1><h2><strong>Ordinačn&iacute; hodiny</strong></h2>', 'yes', 'uvod', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`users_data`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `doctorGroup`, `gender`, `email`, `street`, `city`, `zip`, `region`, `streetInvoice`, `cityInvoice`, `zipInvoice`, `phone`, `ic`, `dic`, `addressMatch`, `lastChange`) VALUES (1, 1, 'Martin', 'Test', 'Ing.', 'Phd.', '302 dětská kardiologie', 'muž', 'zvak.martin@gmail.com', 'Vachova 36/1', 'Brno', '60200', 'jihomoravsky', 'Vachova 36/1', 'Brno', '60200', 737104133, NULL, NULL, NULL, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `doctorGroup`, `gender`, `email`, `street`, `city`, `zip`, `region`, `streetInvoice`, `cityInvoice`, `zipInvoice`, `phone`, `ic`, `dic`, `addressMatch`, `lastChange`) VALUES (2, 2, 'Frantisek', 'Buksantl', 'MUDR.', 'Phd.', '302 dětská kardiologie', NULL, 'zvak.martin@gmail.com', 'Siroka', 'Praha', '66789', 'praha', NULL, NULL, NULL, 998000222, NULL, NULL, NULL, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `doctorGroup`, `gender`, `email`, `street`, `city`, `zip`, `region`, `streetInvoice`, `cityInvoice`, `zipInvoice`, `phone`, `ic`, `dic`, `addressMatch`, `lastChange`) VALUES (3, 3, 'Jozef', 'Kukuricudus', 'Mgr.', 'Phd.', '309 sexuologie', NULL, 'zvak.martin@gmail.com', 'Dlha', 'Ostrava', '44556', 'moravskoslezsky', NULL, NULL, NULL, 222333444, NULL, NULL, NULL, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `doctorGroup`, `gender`, `email`, `street`, `city`, `zip`, `region`, `streetInvoice`, `cityInvoice`, `zipInvoice`, `phone`, `ic`, `dic`, `addressMatch`, `lastChange`) VALUES (5, 4, 'Peter', 'Nagy', 'Bc.', 'Phd.', '208 lékařská genetika', NULL, 'zvak.martin@gmail.com', 'Uzka', 'Plzen', '12345', 'plzensky', NULL, NULL, NULL, 666555777, NULL, NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`users_websiteData`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`users_websiteData` (`id`, `idusers`, `layout`, `layout_group`, `header1`, `header2`, `title`, `description`, `keywords`, `headerImage`, `colourScheme`, `lastChange`) VALUES (1, 1, 'layout_A1', 'all', 'MUDr. Richard Pokorný', 'Kardiologická ambulance', 'MUDr. Richard Pokorný - Kardiologická ambulance', 'Ordinace kardiologie - MUDr. Richard Pokorný', 'EKG, kardiologie, interní, interna, MUDr., lékař, ambulance, ordinace', NULL, NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`guestBook_q`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`guestBook_q` (`id`, `name`, `question`, `dateTime`, `idusers`) VALUES (1, 'marcin', 'jedna', '2011-11-15 17:43:01', 1);
INSERT INTO `mudr`.`guestBook_q` (`id`, `name`, `question`, `dateTime`, `idusers`) VALUES (2, 'erer', 'dva', '2011-11-15 17:53:01', 1);
INSERT INTO `mudr`.`guestBook_q` (`id`, `name`, `question`, `dateTime`, `idusers`) VALUES (3, 'asdf', 'tri', '2011-11-15 18:10:01', 1);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`guestBook_a`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`guestBook_a` (`id`, `idguestBook_q`, `answer`, `dateTime`) VALUES (1, 1, 'mas pravdu', '2011-11-15 18:14:01');
INSERT INTO `mudr`.`guestBook_a` (`id`, `idguestBook_q`, `answer`, `dateTime`) VALUES (2, 1, 'fakljrflasjdf', '2011-11-15 18:15:01');
INSERT INTO `mudr`.`guestBook_a` (`id`, `idguestBook_q`, `answer`, `dateTime`) VALUES (3, 2, '234234234', '2011-11-15 18:20:01');

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`guestBook`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`guestBook` (`id`, `idusers`, `guestBookUserName`, `guestBookPublished`, `lastChange`) VALUES (1, 1, 'Martin Test', 'no', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`resellers`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`resellers` (`id`, `fullName`, `fullAddress`, `accountNumber`, `phone`, `email`, `resellersSponsoringNumber`) VALUES (0, 'admin', 'hidden', '132456789', 123456, 'hidden', NULL);
INSERT INTO `mudr`.`resellers` (`id`, `fullName`, `fullAddress`, `accountNumber`, `phone`, `email`, `resellersSponsoringNumber`) VALUES (1, 'Mgr. Martin Test', 'Muchova 1234/11, 62700 Brno', '132456', 7891011, 'aa@sdf.sl', 'sa34');
INSERT INTO `mudr`.`resellers` (`id`, `fullName`, `fullAddress`, `accountNumber`, `phone`, `email`, `resellersSponsoringNumber`) VALUES (2, 'Ing. Peter Pok', 'Buchalova 23/4, 60200 Brno', '456789', 123456, 'test@sfs.sk', 'le12');

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`lastSearchItems`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`lastSearchItems` (`id`, `searchData`) VALUES (1, 'dummy');

COMMIT;
