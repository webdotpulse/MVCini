CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `items` (`name`, `description`) VALUES
('First Item', 'This is the first demo item.'),
('Second Item', 'Another demo item for testing.');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Password is 'admin'
INSERT INTO `users` (`username`, `password`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
ALTER TABLE `users`
ADD COLUMN `email` varchar(255) NOT NULL AFTER `username`,
ADD COLUMN `role` enum('user','superuser','admin') NOT NULL DEFAULT 'user' AFTER `password`,
ADD COLUMN `is_verified` tinyint(1) NOT NULL DEFAULT 0 AFTER `role`,
ADD COLUMN `verification_token` varchar(64) DEFAULT NULL AFTER `is_verified`,
ADD COLUMN `reset_token` varchar(64) DEFAULT NULL AFTER `verification_token`,
ADD COLUMN `reset_token_expires` datetime DEFAULT NULL AFTER `reset_token`,
ADD COLUMN `failed_attempts` int(11) NOT NULL DEFAULT 0 AFTER `reset_token_expires`,
ADD COLUMN `last_failed_login` datetime DEFAULT NULL AFTER `failed_attempts`,
ADD COLUMN `avatar` varchar(255) DEFAULT NULL AFTER `last_failed_login`;

UPDATE `users` SET `email` = 'admin@example.com', `role` = 'admin', `is_verified` = 1 WHERE `username` = 'admin';

CREATE TABLE IF NOT EXISTS `remember_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
