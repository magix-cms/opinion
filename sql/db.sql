CREATE TABLE IF NOT EXISTS `mc_catalog_opinion` (
  `idopinion` INT(7) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `idcatalog` INT(6) UNSIGNED NOT NULL ,
  `pseudo_opinion` varchar(100) NOT NULL,
  `email_opinion` varchar(150) NOT NULL,
  `msg_opinion` TEXT NOT NULL ,
  `rating_opinion` SMALLINT(3) UNSIGNED NOT NULL DEFAULT 0 ,
  `status_opinion` SMALLINT(1) UNSIGNED NOT NULL DEFAULT 0 ,
  `date_opinion` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`idopinion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;