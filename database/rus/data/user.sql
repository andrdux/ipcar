USE `ipcar`;

-- Credentials:
-- Email: admin@admin.com'
-- Password: 1
INSERT INTO `user` (`id`,`fio`,`email`,`password`,`phone1`,`phone2`,`active`,`role`,`ip`,`updated`) VALUES (NULL,'Admin','admin@admin.com','356a192b7913b04c54574d18c28d46e6395428ab','1','1',1,1,'192.168.1.1',NOW());