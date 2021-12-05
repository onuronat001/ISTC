SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- ----------------------------
-- Records of customers
-- ----------------------------
BEGIN;
INSERT INTO `customers` VALUES (1, 'Türker Jöntürk', '2014-06-28', 492.12);
INSERT INTO `customers` VALUES (2, 'Kaptan Devopuz', '2015-01-15', 1505.95);
INSERT INTO `customers` VALUES (3, 'İsa Sonuyumaz', '2016-02-11', 0.00);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
