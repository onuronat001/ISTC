SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
-- ----------------------------
-- Records of products
-- ----------------------------
BEGIN;
INSERT INTO `products` VALUES (100, 'Black&Decker A7062 40 Parça Cırcırlı Tornavida Seti', 1, 120.75, 0);
INSERT INTO `products` VALUES (101, 'Reko Mini Tamir Hassas Tornavida Seti 32\'li', 1, 49.50, 8);
INSERT INTO `products` VALUES (102, 'Viko Karre Anahtar - Beyaz', 2, 11.28, 2);
INSERT INTO `products` VALUES (103, 'Legrand Salbei Anahtar, Alüminyum', 2, 22.80, 10);
INSERT INTO `products` VALUES (104, 'Schneider Asfora Beyaz Komütatör', 2, 12.95, 10);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
