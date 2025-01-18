-- MariaDB database creation script for Eggdrop
-- Author: Markus (markus@ynm.hu)
-- Copyright: 2024, YnM. All rights reserved.



CREATE DATABASE IF NOT EXISTS `ynmegg` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ynmegg`;

-------------------------Felhasználók táblája-------------------------------

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `token_hash` TEXT NOT NULL,
  `verified` INT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-------------------------Botok táblája------------------------------

CREATE TABLE IF NOT EXISTS `bots` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `user_id` INT NOT NULL,
  `active` TINYINT(1) NOT NULL,
  `key_hash` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

----------------------Rendszer állapot táblája-------------------------

CREATE TABLE IF NOT EXISTS `system_status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bot_id` INT NOT NULL,
  `timestamp` INT NOT NULL,  -- Az aktuális időbélyeg
  `uptime_seconds` INT NOT NULL,  -- Rendszer uptime (másodpercekben)
  `cpu_usage` FLOAT NOT NULL,  -- CPU használat (%) formátumban
  `ram_usage` FLOAT NOT NULL,  -- RAM használat (%) formátumban
  `disk_usage` FLOAT NOT NULL,  -- Lemezhasználat (%)
  `temperature` FLOAT DEFAULT NULL,  -- Ha van, gép hőmérséklete (Celsius-ban)
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bot_id`) REFERENCES `bots`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--------------------Chatlog tábla-----------------------------

CREATE TABLE IF NOT EXISTS `chat_logs` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bot_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `message` TEXT NOT NULL,  -- A felhasználó üzenete
  `timestamp` INT NOT NULL,  -- Üzenet időbélyeg
  `is_command` TINYINT(1) NOT NULL,  -- Jelezzük, hogy a üzenet parancs volt-e
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bot_id`) REFERENCES `bots`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-------------------Folyamatok táblája-----------------------------

CREATE TABLE IF NOT EXISTS `processes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bot_id` INT NOT NULL,
  `process_name` VARCHAR(255) NOT NULL,  -- Folyamat neve
  `pid` INT NOT NULL,  -- Process ID
  `cpu_usage` FLOAT NOT NULL,  -- CPU használat (%) a folyamatra vonatkozóan
  `ram_usage` FLOAT NOT NULL,  -- RAM használat (%) a folyamatra vonatkozóan
  `status` VARCHAR(50) NOT NULL,  -- Folyamat állapota (running, sleeping, stb.)
  `timestamp` INT NOT NULL,  -- Folyamat frissítésének időbélyege
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bot_id`) REFERENCES `bots`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

---------------Bot parancsok naplója-----------------------------

CREATE TABLE IF NOT EXISTS `bot_commands_log` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bot_id` INT NOT NULL,
  `command` VARCHAR(255) NOT NULL,  -- Parancs neve
  `arguments` TEXT,  -- A parancs paraméterei
  `executed_at` INT NOT NULL,  -- A parancs végrehajtásának időpontja
  `executed_by` INT NOT NULL,  -- A felhasználó, aki kiadta a parancsot
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bot_id`) REFERENCES `bots`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`executed_by`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

----------------Bot konfigurációs táblája-----------------------------

CREATE TABLE IF NOT EXISTS `bot_config` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `bot_id` INT NOT NULL,
  `config_key` VARCHAR(255) NOT NULL,  -- Beállítás kulcs
  `config_value` TEXT NOT NULL,  -- Beállítás érték
  PRIMARY KEY (`id`),
  FOREIGN KEY (`bot_id`) REFERENCES `bots`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Az adatbázis véglegesítése
COMMIT;
