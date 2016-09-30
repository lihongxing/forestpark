-- -------------------------------------------
SET AUTOCOMMIT=0;
START TRANSACTION;
SET SQL_QUOTE_SHOW_CREATE = 1;
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
-- -------------------------------------------
-- -------------------------------------------
-- START BACKUP
-- -------------------------------------------
-- -------------------------------------------
-- TABLE `dp_upload`
-- -------------------------------------------
DROP TABLE IF EXISTS `dp_upload`;
CREATE TABLE IF NOT EXISTS `dp_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk COMMENT='文件上传表';

-- -------------------------------------------
-- TABLE DATA dp_upload
-- -------------------------------------------
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('4','1F31Q0D-18.jpg','/attachement/image/1/2016/08/1F31Q0D-18.jpg','1','1470040398','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('5','2012211134117117.jpg','/attachement/image/1/2016/08/2012211134117117.jpg','1','1470040437','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('6','2013062417332389.jpg','/attachement/image/1/2016/08/2013062417332389.jpg','1','1470040683','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('8','t01772ad91bbd9bf0ca.png','/attachement/image/1/2016/08/t01772ad91bbd9bf0ca.png','1','1470040898','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('9','20116162135914.jpg','/attachement/image/1/2016/08/20116162135914.jpg','1','1470041102','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('10','3_140702143852_3.jpg','/attachement/image/1/2016/08/3_140702143852_3.jpg','1','1470041200','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('12','29-173158_318.jpg','/attachement/image/1/2016/08/29-173158_318.jpg','1','1470041316','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('13','21543434e-0-lp.jpg','/attachement/image/1/2016/08/21543434e-0-lp.jpg','1','1470041327','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('21','headimg_17.jpg','/attachement/image/37/2016/08/headimg_17.jpg','1','1471170150','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('22','headimg_18.jpg','/attachement/image/38/2016/08/headimg_18.jpg','1','1471170634','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('23','2013062417332387.jpg','/attachement/image/1/2016/08/18/2013062417332387.jpg','1','1471488264','1');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('24','t013a23850c1e70d83b.jpg','/attachement/image/admin/2016/08/18/t013a23850c1e70d83b.jpg','1','1471488352','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('25','2012211134117117.jpg','/attachement/image/admin/2016/08/18/2012211134117117.jpg','1','1471514075','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('26','20131981535211.jpg','/attachement/image/admin/2016/08/18/20131981535211.jpg','1','1471514749','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('27','2013062417332389.jpg','/attachement/image/admin/2016/08/19/2013062417332389.jpg','1','1471575669','0');
INSERT INTO `dp_upload` (`id`,`filename`,`attachment`,`type`,`createtime`,`uniacid`) VALUES
('32','sw6u7Fl87w8uLHkvah8k6fUsH3auth.jpg','/attachement/image/admin/2016/08/31/sw6u7Fl87w8uLHkvah8k6fUsH3auth.jpg','1','1472608527','0');



-- -------------------------------------------
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
COMMIT;
-- -------------------------------------------
-- -------------------------------------------
-- END BACKUP
-- -------------------------------------------
