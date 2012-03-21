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
  `salt` VARCHAR(20) NULL ,
  `role` ENUM('uživatel','admin') NULL ,
  `usersSponsor` INT NULL ,
  `usersSponsoringNumber` VARCHAR(4) NULL ,
  `superUserActive` TINYINT(1)  NULL ,
  `subdomain` VARCHAR(30) NULL ,
  `dateOfRegistration` DATETIME NULL ,
  `program` VARCHAR(25) NULL ,
  `advertisement` VARCHAR(25) NULL ,
  `registrationToken` VARCHAR(45) NULL ,
  `dateOfActivation` DATETIME NULL ,
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
  `gender` ENUM('muž','žena') NULL ,
  `email` VARCHAR(50) NULL ,
  `street` VARCHAR(50) NULL ,
  `city` VARCHAR(50) NULL ,
  `zip` VARCHAR(8) NULL ,
  `region` VARCHAR(30) NULL ,
  `phone` INT NULL ,
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

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `salt`, `role`, `usersSponsor`, `usersSponsoringNumber`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (0, '', 'admin', 'e18290be7eb5f8be11bb4c9ed494f354f3915c4f', '@Xw^~Mr4L60o;E1n', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `salt`, `role`, `usersSponsor`, `usersSponsoringNumber`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (1, 'active', 'xa', '7afa699467e313cd534eed0dcd24291e6071d25a', 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, '1234', 0, 'xa', '2011-12-01 00:00:00', 'demo', 'zentiva', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', NULL, '2011-10-01', '2012-09-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `salt`, `role`, `usersSponsor`, `usersSponsoringNumber`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (2, 'active', 'mskdfdj', '7afa699467e313cd534eed0dcd24291e6071d25a', 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, '5678', 0, 'tutururu', '2011-04-01 00:00:00', 'basic', 'no', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', NULL, '2012-01-17', '2012-10-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `salt`, `role`, `usersSponsor`, `usersSponsoringNumber`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (3, 'active', 'tester', '7afa699467e313cd534eed0dcd24291e6071d25a', 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, '1222', 0, 'tester11', '2011-12-17 00:00:00', 'premium', 'no', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', NULL, '2012-01-27', '2012-11-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);
INSERT INTO `mudr`.`users` (`id`, `accountStatus`, `username`, `password`, `salt`, `role`, `usersSponsor`, `usersSponsoringNumber`, `superUserActive`, `subdomain`, `dateOfRegistration`, `program`, `advertisement`, `registrationToken`, `dateOfActivation`, `dateFrom`, `dateTo`, `passwordChanged`, `lastLogin`, `lastLogout`, `passwordResent`, `maintenanceMode`, `subdomainStatus`, `realSubdomainStatus`, `notificationCounter`, `notificationDate`) VALUES (4, 'active', 'ester1', '7afa699467e313cd534eed0dcd24291e6071d25a', 'C(l2=iW4HN;9zr1!', 'uživatel', NULL, '1344', 0, 'mrkva321', '2011-12-01 00:00:00', 'demo', 'wallmark', 'f8f1259025b54faeeceb5acb7c55ff5ceee122da', NULL, '2012-02-04', '2012-12-27', NULL, NULL, NULL, '1971-00-00 00:00:00', 'off', 'Valid', 'Valid', NULL, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`menuItems`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (2, 1, 2, 'Tým', '<p><span class=\"Apple-style-span\" style=\"font-family: Verdana, Arial, sans-serif; font-size: x-small;\"><br /><img style=\"float: left; margin-left: 7px; margin-right: 7px;\" src=\"http://www.kardiologie-pokorny.ic.cz/img/1_RP.jpg\" alt=\"\" width=\"96\" height=\"117\" /></span></p>', 'yes', 'tym', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (3, 1, 3, 'Smluvní pojišťovny', '<h2>Smluvn&iacute; poji&scaron;ťovny</h2><p>&nbsp;</p><p>Jsme smluvn&iacute;m partnerem těchto zdravotn&iacute;ch poji&scaron;ťoven:<br /><br />111 &ndash; V&scaron;eobecn&aacute; zdravotn&iacute; poji&scaron;ťovna&nbsp;<br /><br />201 &ndash; Vojensk&aacute; zdravotn&iacute; poji&scaron;ťovna&nbsp;<br /><br />205 &ndash; Česk&aacute; průmyslov&aacute; zdravotn&iacute; poji&scaron;ťovna&nbsp;<br /><br />207 &ndash; Oborov&aacute; zdravotn&iacute; poji&scaron;ťovna zaměstnanců bank, poji&scaron;ťoven a stavebnictv&iacute;&nbsp;<br /><br />211 &ndash; Zdravotn&iacute; poji&scaron;ťovna Ministerstva vnitra&nbsp;<br /><br />217 &ndash; Zdravotn&iacute; poji&scaron;ťovna METAL-ALIANCE&nbsp;<br /><br />Po předchoz&iacute; domluvě o&scaron;etř&iacute;me i pacienty ostatn&iacute;ch zdravotn&iacute;ch poji&scaron;ťoven</p>', 'yes', 'smluvni-pojistovny', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (4, 1, 4, 'Vizitka', '<h2>MUDr. Richard Pokorn&yacute;</h2><p>&nbsp;</p><p>Jasm&iacute;nov&aacute; 1145/4<br />251 01 Ř&iacute;čany u Prahy<br />e-mail: mudr.pokorny(at)gmail.com<br />tel.: +420 323 631 945&nbsp;<br /><br /></p><h2>Ordinačn&iacute; doba</h2><p>&nbsp;</p><p>Po 7:30 &mdash; 13:00<br />&Uacute;t 7:30 &mdash; 13:00<br />St pouze objednan&iacute; pacienti<br />Čt 7:30 &mdash; 12:00 13:30 &mdash; 16:30 echo 17:00 &mdash; 21:00<br />P&aacute; 7:30 &mdash; 13:00<br /><br /></p><h2>Smluvn&iacute; poji&scaron;ťovny</h2><p>&nbsp;</p><p>111, 201, 205, 207, 211, 217<br />Přij&iacute;m&aacute;me i nepoji&scaron;těn&eacute; pacienty za př&iacute;mou &uacute;hradu. Ceny za jednotliv&eacute; v&yacute;kony odpov&iacute;daj&iacute; &uacute;hradě VZP.</p>', 'yes', 'vizitka', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (5, 1, 5, 'P5', '1234', 'no', 'polozka5', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (6, 1, 6, 'P6', '5678', 'no', 'polozka6', NULL);
INSERT INTO `mudr`.`menuItems` (`id`, `idusers`, `itemId`, `itemName`, `itemContent`, `itemPublished`, `itemNameRouteCs`, `lastChange`) VALUES (8, 1, 1, 'Úvod', '<p>V&iacute;t&aacute;me V&aacute;s na str&aacute;nk&aacute;ch na&scaron;&iacute; kardiologick&eacute; ambulance. Dovolte abychom představili portfolio na&scaron;ich činnost&iacute;.<br /><br /></p><h2>Klinick&eacute; vy&scaron;etřen&iacute;</h2><p>&nbsp;</p><p>pohovor s pacientem - zji&scaron;těn&iacute; rizikov&yacute;ch faktorů, zji&scaron;těn&iacute; prodělan&yacute;ch chorob a l&eacute;čby, rozbor obt&iacute;ž&iacute; objektivn&iacute; vy&scaron;etřen&iacute;&nbsp;<br /><br /></p><h2>EKG</h2><p>&nbsp;</p><p>metoda, kter&aacute; sn&iacute;m&aacute; z povrchu těla elektrick&eacute; potenci&aacute;ly přenesen&eacute; ze srdce, a na jejich z&aacute;kladě zji&scaron;ťuje př&iacute;padn&eacute; poruchy srdečn&iacute;ho rytmu, poruchy veden&iacute; srdečn&iacute;ch vzruchů a změny tvaru křivky, kter&eacute; způsobuj&iacute; někter&eacute; choroby&nbsp;<br /><br /></p><h2>Z&aacute;těžov&eacute; EKG (ergometrie)</h2><p>&nbsp;</p><p>sn&iacute;m&aacute;n&iacute; ekg, měřen&iacute; krevn&iacute;ho tlaku a sledov&aacute;n&iacute; obt&iacute;ž&iacute; pacienta při postupně se zvy&scaron;uj&iacute;c&iacute; z&aacute;těži na bicyklov&eacute;m ergometru a pot&eacute; v zotavovac&iacute; f&aacute;zi v klidu, vy&scaron;etřen&iacute; se prov&aacute;d&iacute; hlavně v r&aacute;mci diagnostiky ischemick&eacute; choroby srdečn&iacute; a při zji&scaron;ťov&aacute;n&iacute; poruch rytmu při z&aacute;těži u někter&yacute;ch chorob&nbsp;<br /><br /><span class=\"bold\">Echokardiografick&eacute; vy&scaron;etřen&iacute;</span>&nbsp;ultrazvukov&eacute; tj. neinvazivn&iacute; vy&scaron;etřen&iacute; srdce, kter&eacute; měř&iacute; velikost srdečn&iacute;ch odd&iacute;lů, hodnot&iacute; v&yacute;konnost srdce, hodnot&iacute; funkci chlopn&iacute;, zji&scaron;ťuje patologick&eacute; &uacute;tvary v srdci...&nbsp;<br /><br /><span class=\"bold\">24 hodinov&aacute; monitorace krevn&iacute;ho tlaku a ekg (holter)</span>&nbsp;vy&scaron;etřen&iacute; smluvně zaji&scaron;těno v Ř&iacute;čanech&nbsp;<br /><br /><span class=\"bold\">Dispenzarizace pacientů (tj. sledov&aacute;n&iacute; a l&eacute;čba)</span><br />pacienti se srdečn&iacute;mi vadami a po operaci srdečn&iacute;ch vad&nbsp;<br />pacienti s ischemickou chorobou srdečn&iacute;, stavy po revaskularizačn&iacute;ch v&yacute;konech (aortokoron&aacute;rn&iacute;m bypassu a angioplastice)&nbsp;<br />pacienti s kardiomyopatiemi&nbsp;<br />pacienti s implantovan&yacute;m kardiostimul&aacute;torem&nbsp;<br />pacienti s poruchami srdečn&iacute;ho rytmu&nbsp;<br />pacienti s vysok&yacute;m krevn&iacute;m tlakem&nbsp;<br />pacienti s poruchou metabolizmu tuků&nbsp;<br /><br />Spolupr&aacute;ce s vět&scaron;inou kardiocenter v Praze</p>', 'yes', 'uvod', NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `mudr`.`users_data`
-- -----------------------------------------------------
START TRANSACTION;
USE `mudr`;
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `gender`, `email`, `street`, `city`, `zip`, `region`, `phone`, `lastChange`) VALUES (1, 1, 'Martin', 'Test', 'Ing.', 'Phd.', 'muž', 'zvak.martin@gmail.com', 'Vachova 36/1', 'Brno', '60200', 'jihomoravsky', 737104133, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `gender`, `email`, `street`, `city`, `zip`, `region`, `phone`, `lastChange`) VALUES (2, 2, 'Frantisek', 'Buksantl', 'MUDR.', 'Phd.', NULL, 'zvak.martin@gmail.com', 'Siroka', 'Praha', '66789', 'praha', 998000222, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `gender`, `email`, `street`, `city`, `zip`, `region`, `phone`, `lastChange`) VALUES (3, 3, 'Jozef', 'Kukuricudus', 'Mgr.', 'Phd.', NULL, 'zvak.martin@gmail.com', 'Dlha', 'Ostrava', '44556', 'moravskoslezsky', 222333444, NULL);
INSERT INTO `mudr`.`users_data` (`id`, `idusers`, `name`, `surname`, `titleBefore`, `titleAfter`, `gender`, `email`, `street`, `city`, `zip`, `region`, `phone`, `lastChange`) VALUES (5, 4, 'Peter', 'Nagy', 'Bc.', 'Phd.', NULL, 'zvak.martin@gmail.com', 'Uzka', 'Plzen', '12345', 'plzensky', 666555777, NULL);

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
