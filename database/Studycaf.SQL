/*
Created: 04/09/2024
Modified: 15/10/2024
Model: Studycaf
Database: MySQL 8.0
*/

-- Create tables section -------------------------------------------------

-- Table citys

CREATE TABLE `citys`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(20) NOT NULL,
  `postal_no` Int NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- Table users

CREATE TABLE `users`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `email` Varchar(80) NOT NULL,
  `password` Varchar(300) NOT NULL,
  `address` Varchar(50) NOT NULL,
  `city_id` Int,
  `picture_certificate_id` Int,
  `gender_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship3` ON `users` (`city_id`)
;

CREATE INDEX `IX_Relationship4` ON `users` (`picture_certificate_id`)
;

CREATE INDEX `IX_Relationship14` ON `users` (`gender_id`)
;

-- Table roles

CREATE TABLE `roles`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(20) NOT NULL,
  `description` Varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- Table user_role

CREATE TABLE `user_role`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `user_id` Int,
  `role_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship1` ON `user_role` (`user_id`)
;

CREATE INDEX `IX_Relationship2` ON `user_role` (`role_id`)
;

-- Table products

CREATE TABLE `products`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(20) NOT NULL,
  `description` Varchar(100) NOT NULL,
  `price` Double NOT NULL,
  `student_price` Double NOT NULL,
  `picture_product_id` Int,
  `category_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship5` ON `products` (`picture_product_id`)
;

CREATE INDEX `IX_Relationship6` ON `products` (`category_id`)
;

-- Table categories

CREATE TABLE `categories`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(20) NOT NULL,
  `description` Varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- Table tables

CREATE TABLE `tables`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `chair_no` Int NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- Table pictures

CREATE TABLE `pictures`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `file_name` Varchar(200) NOT NULL,
  `description` Varchar(50),
  PRIMARY KEY (`id`)
)
;

-- Table reservations

CREATE TABLE `reservations`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `time_res` Timestamp NOT NULL,
  `table_id` Int,
  `user_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship7` ON `reservations` (`table_id`)
;

CREATE INDEX `IX_Relationship8` ON `reservations` (`user_id`)
;

-- Table points

CREATE TABLE `points`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `point_no` Int NOT NULL,
  `user_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship9` ON `points` (`user_id`)
;

-- Table coupons

CREATE TABLE `coupons`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(30) NOT NULL,
  `description` Char(200) NOT NULL,
  `user_role_id` Int,
  `picture_id` Int,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship10` ON `coupons` (`user_role_id`)
;

CREATE INDEX `IX_Relationship13` ON `coupons` (`picture_id`)
;

-- Table product_coupon

CREATE TABLE `product_coupon`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `product_id` Int,
  `coupon_id` Int,
  `price_dis` Double NOT NULL,
  PRIMARY KEY (`id`)
)
;

CREATE INDEX `IX_Relationship11` ON `product_coupon` (`product_id`)
;

CREATE INDEX `IX_Relationship12` ON `product_coupon` (`coupon_id`)
;

-- Table genders

CREATE TABLE `genders`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `name` Varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
)
;

-- Table password_resets

CREATE TABLE `password_resets`
(
  `id` Int NOT NULL AUTO_INCREMENT,
  `email` Varchar(255) NOT NULL,
  `token` Varchar(255) NOT NULL,
  `created_at` Timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
;

-- Create foreign keys (relationships) section -------------------------------------------------

ALTER TABLE `user_role` ADD CONSTRAINT `Relationship1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `user_role` ADD CONSTRAINT `Relationship2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `users` ADD CONSTRAINT `Relationship3` FOREIGN KEY (`city_id`) REFERENCES `citys` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `users` ADD CONSTRAINT `Relationship4` FOREIGN KEY (`picture_certificate_id`) REFERENCES `pictures` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `products` ADD CONSTRAINT `Relationship5` FOREIGN KEY (`picture_product_id`) REFERENCES `pictures` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `products` ADD CONSTRAINT `Relationship6` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `reservations` ADD CONSTRAINT `Relationship7` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `reservations` ADD CONSTRAINT `Relationship8` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `points` ADD CONSTRAINT `Relationship9` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `coupons` ADD CONSTRAINT `Relationship10` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `product_coupon` ADD CONSTRAINT `Relationship11` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `product_coupon` ADD CONSTRAINT `Relationship12` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `coupons` ADD CONSTRAINT `Relationship13` FOREIGN KEY (`picture_id`) REFERENCES `pictures` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;

ALTER TABLE `users` ADD CONSTRAINT `Relationship14` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
;


