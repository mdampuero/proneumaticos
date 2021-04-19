ALTER TABLE `sg_sinister` ADD COLUMN `si_days` INT(11) NOT NULL DEFAULT 0 AFTER `si_finish_payment`;
UPDATE proneumaticos.sg_sinister SET si_days=CONCAT(datediff(FROM_UNIXTIME(si_modified,'%Y-%m-%d'),si_date),'') WHERE si_status IN (60,5);

ALTER TABLE `proneumaticos`.`sg_sinister` 
ADD COLUMN `si_st_id` INT(11) NULL DEFAULT 0 AFTER `si_customer_address`;

ALTER TABLE `sg_sinister` 
ADD COLUMN `si_delivery` INT(11) NOT NULL DEFAULT 0 AFTER `si_customer_address`,
ADD COLUMN `si_city` VARCHAR(255) NULL DEFAULT NULL AFTER `si_st_id`;


DROP TABLE IF EXISTS `sg_state`;
CREATE TABLE IF NOT EXISTS `sg_state` (
  `st_id` int(10) NOT NULL AUTO_INCREMENT,
  `st_state` varchar(255) NOT NULL,
  `st_status` tinyint(4) NOT NULL,
  `st_created` varchar(64) NOT NULL,
  `st_modified` varchar(64) NOT NULL,
  `st_deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`st_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `provincias`
--

INSERT INTO `sg_state` (`st_id`, `st_state`,`st_status`,`st_created`,`st_modified`,`st_deleted`) VALUES
(1, 'Buenos Aires',1,'231321321','2133213123',0),
(2, 'Buenos Aires-GBA',1,'231321321','2133213123',0),
(3, 'Capital Federal',1,'231321321','2133213123',0),
(4, 'Catamarca',1,'231321321','2133213123',0),
(5, 'Chaco',1,'231321321','2133213123',0),
(6, 'Chubut',1,'231321321','2133213123',0),
(7, 'Cordoba',1,'231321321','2133213123',0),
(8, 'Corrientes',1,'231321321','2133213123',0),
(9, 'Entre Rios',1,'231321321','2133213123',0),
(10, 'Formosa',1,'231321321','2133213123',0),
(11, 'Jujuy',1,'231321321','2133213123',0),
(12, 'La Pampa',1,'231321321','2133213123',0),
(13, 'La Rioja',1,'231321321','2133213123',0),
(14, 'Mendoza',1,'231321321','2133213123',0),
(15, 'Misiones',1,'231321321','2133213123',0),
(16, 'Neuquen',1,'231321321','2133213123',0),
(17, 'Rio Negro',1,'231321321','2133213123',0),
(18, 'Salta',1,'231321321','2133213123',0),
(19, 'San Juan',1,'231321321','2133213123',0),
(20, 'San Luis',1,'231321321','2133213123',0),
(21, 'Santa Cruz',1,'231321321','2133213123',0),
(22, 'Santa Fe',1,'231321321','2133213123',0),
(23, 'Santiago del Estero',1,'231321321','2133213123',0),
(24, 'Tierra del Fuego',1,'231321321','2133213123',0),
(25, 'Tucuman',1,'231321321','2133213123',0);



DROP TABLE IF EXISTS `sg_transport`;
CREATE TABLE IF NOT EXISTS `sg_transport` (
  `tr_id` int(10) NOT NULL AUTO_INCREMENT,
  `tr_transport` varchar(255) NOT NULL,
  `tr_status` tinyint(4) NOT NULL,
  `tr_created` varchar(64) NOT NULL,
  `tr_modified` varchar(64) NOT NULL,
  `tr_deleted` tinyint(4) NOT NULL,
  PRIMARY KEY (`tr_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

ALTER TABLE `sg_sinister` 
ADD COLUMN `si_delivery` TINYINT(4) NOT NULL AFTER `si_deleted`;
ALTER TABLE `sg_sinister` 
ADD COLUMN `si_tr_id` INT(11) NOT NULL DEFAULT 0 AFTER `si_delivery`;
ALTER TABLE `sg_sinister` 
ADD COLUMN `si_track_id` VARCHAR(128) NULL DEFAULT NULL AFTER `si_tr_id`;
ALTER TABLE `sg_sinister` 
ADD COLUMN `si_delivery_name` VARCHAR(255) NULL DEFAULT NULL AFTER `si_track_id`,
ADD COLUMN `si_delivery_document` VARCHAR(128) NULL AFTER `si_delivery_name`;

ALTER TABLE `sg_tbranch` 
ADD COLUMN `tb_code` VARCHAR(45) NULL DEFAULT NULL AFTER `tb_deleted`;
ALTER TABLE `sg_tbranch` 
CHANGE COLUMN `tb_code` `tb_code` VARCHAR(45) NULL DEFAULT NULL AFTER `tb_name`;

ALTER TABLE `sg_cbranch` 
ADD COLUMN `cb_code` VARCHAR(45) NULL DEFAULT NULL AFTER `cb_name`;


ALTER TABLE `sg_bbranch` 
ADD COLUMN `bb_code` VARCHAR(45) NULL DEFAULT NULL AFTER `bb_name`;


ALTER TABLE `sg_accesory` 
ADD COLUMN `ac_code` VARCHAR(45) NULL DEFAULT NULL AFTER `ac_name`;


ALTER TABLE `sg_sinister` 
ADD COLUMN `si_au_number` VARCHAR(45) NULL DEFAULT NULL AFTER `si_delivery_document`,
ADD COLUMN `si_au_price_sale` FLOAT NULL DEFAULT NULL AFTER `si_au_number`,
ADD COLUMN `si_au_price_cost` FLOAT NULL DEFAULT NULL AFTER `si_au_price_sale`;


ALTER TABLE `sg_sinister` 
ADD COLUMN `si_po_number` VARCHAR(45) NULL DEFAULT NULL AFTER `si_delivery_document`,
ADD COLUMN `si_po_price_sale` FLOAT NULL DEFAULT NULL AFTER `si_po_number`,
ADD COLUMN `si_po_price_cost` FLOAT NULL DEFAULT NULL AFTER `si_po_price_sale`;

ALTER TABLE `sg_sinister` 
ADD COLUMN `si_co_number` VARCHAR(45) NULL DEFAULT NULL AFTER `si_delivery_document`,
ADD COLUMN `si_co_price_sale` FLOAT NULL DEFAULT NULL AFTER `si_co_number`,
ADD COLUMN `si_co_price_cost` FLOAT NULL DEFAULT NULL AFTER `si_co_price_sale`;

ALTER TABLE `sg_sinister` 
ADD COLUMN `si_ba_number` VARCHAR(45) NULL DEFAULT NULL AFTER `si_delivery_document`,
ADD COLUMN `si_ba_price_sale` FLOAT NULL DEFAULT NULL AFTER `si_ba_number`,
ADD COLUMN `si_ba_price_cost` FLOAT NULL DEFAULT NULL AFTER `si_ba_price_sale`;


ALTER TABLE `sg_sinister_accesory` 
ADD COLUMN `sa_price_cost` FLOAT NULL DEFAULT NULL AFTER `sa_transport`,
ADD COLUMN `sa_price_sale` FLOAT NULL DEFAULT NULL AFTER `sa_price_cost`,
ADD COLUMN `sa_number` VARCHAR(45) NULL DEFAULT NULL AFTER `sa_price_sale`;


ALTER TABLE `sg_sinister` 
ADD COLUMN `si_total_cost` FLOAT NULL DEFAULT 0 AFTER `si_au_price_cost`,
ADD COLUMN `si_total_sale` FLOAT NULL DEFAULT 0 AFTER `si_total_cost`;
