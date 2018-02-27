CREATE TABLE IF NOT EXISTS `mc_opinion` (
  `id_opinion` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_product` int(11) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `pseudo_opinion` varchar(100) NOT NULL,
  `email_opinion` varchar(150) NOT NULL,
  `msg_opinion` text NOT NULL,
  `rating_opinion` decimal(1,1) unsigned NOT NULL DEFAULT 0,
  `status_opinion` smallint(1) unsigned NOT NULL DEFAULT 0,
  `date_opinion` timestamp NOT NULL,
  PRIMARY KEY (`id_opinion`),
  KEY `id_lang` (`id_lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

ALTER TABLE `mc_opinion`
  ADD CONSTRAINT `mc_opinion_ibfk_1` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'opinion';