-- Database: db_quanlythuvien (MySQL 8+)
-- This schema follows the ERD provided: Category, Book, Reader, Staff, Role, Permission, Role_Permission, Borrow, Borrow_detail, Fine
-- Notes:
-- - Keys are VARCHAR to match the diagram (IDs are application-generated).
-- - Borrow.status implemented as ENUM for borrow_status.
-- - Borrow.fine_id is kept as a nullable column (per diagram) but not a foreign key to avoid circular FK with Fine.
-- - utf8mb4 is used throughout.

CREATE DATABASE IF NOT EXISTS `db_quanlythuvien`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE `db_quanlythuvien`;

-- CATEGORY
CREATE TABLE IF NOT EXISTS `Category` (
  `category_id` VARCHAR(50) PRIMARY KEY,
  `category_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ROLE
CREATE TABLE IF NOT EXISTS `Role` (
  `role_id` VARCHAR(50) PRIMARY KEY,
  `role_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PERMISSION
CREATE TABLE IF NOT EXISTS `Permission` (
  `permission_id` VARCHAR(50) PRIMARY KEY,
  `permission_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(255) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- READER
CREATE TABLE IF NOT EXISTS `Reader` (
  `reader_id` VARCHAR(50) PRIMARY KEY,
  `reader_name` VARCHAR(25) NOT NULL,
  `birthday` DATE NULL,
  `phone` VARCHAR(10) NULL,
  `email` VARCHAR(50) NULL,
  CONSTRAINT `uk_reader_phone` UNIQUE (`phone`),
  CONSTRAINT `uk_reader_email` UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- STAFF
CREATE TABLE IF NOT EXISTS `Staff` (
  `staff_id` VARCHAR(50) PRIMARY KEY,
  `staff_name` VARCHAR(25) NOT NULL,
  `role_id` VARCHAR(50) NOT NULL,
  `position` VARCHAR(100) NULL,
  `phone` VARCHAR(10) NULL,
  `email` VARCHAR(100) NULL,
  CONSTRAINT `fk_staff_role` FOREIGN KEY (`role_id`) REFERENCES `Role`(`role_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `uk_staff_phone` UNIQUE (`phone`),
  CONSTRAINT `uk_staff_email` UNIQUE (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BOOK
CREATE TABLE IF NOT EXISTS `Book` (
  `book_id` VARCHAR(50) PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `author` VARCHAR(25) NULL,
  `publisher` VARCHAR(25) NULL,
  `publish_year` INT NULL,
  `category` VARCHAR(50) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 0,
  CONSTRAINT `fk_book_category` FOREIGN KEY (`category`) REFERENCES `Category`(`category_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_book_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ROLE_PERMISSION (bridge)
CREATE TABLE IF NOT EXISTS `Role_Permission` (
  `role_id` VARCHAR(50) NOT NULL,
  `permission_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  CONSTRAINT `fk_rp_role` FOREIGN KEY (`role_id`) REFERENCES `Role`(`role_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_rp_perm` FOREIGN KEY (`permission_id`) REFERENCES `Permission`(`permission_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BORROW
CREATE TABLE IF NOT EXISTS `Borrow` (
  `borrow_id` VARCHAR(50) PRIMARY KEY,
  `reader_id` VARCHAR(50) NOT NULL,
  `staff_id` VARCHAR(50) NOT NULL,
  `borrow_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `return_date` DATE NULL,
  `status` ENUM('BORROWED','RETURNED','OVERDUE','CANCELLED') NOT NULL DEFAULT 'BORROWED',
  `fine_id` VARCHAR(50) NULL, -- kept for compatibility with the ERD; not an FK to avoid circular dependency
  CONSTRAINT `fk_borrow_reader` FOREIGN KEY (`reader_id`) REFERENCES `Reader`(`reader_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT `fk_borrow_staff` FOREIGN KEY (`staff_id`) REFERENCES `Staff`(`staff_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_borrow_reader` (`reader_id`),
  INDEX `idx_borrow_staff` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- BORROW_DETAIL
CREATE TABLE IF NOT EXISTS `Borrow_detail` (
  `borrow_id` VARCHAR(50) NOT NULL,
  `book_id` VARCHAR(50) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`borrow_id`, `book_id`),
  CONSTRAINT `fk_bd_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `Borrow`(`borrow_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT `fk_bd_book` FOREIGN KEY (`book_id`) REFERENCES `Book`(`book_id`) ON UPDATE CASCADE ON DELETE RESTRICT,
  INDEX `idx_bd_book` (`book_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FINE
CREATE TABLE IF NOT EXISTS `Fine` (
  `fine_id` VARCHAR(50) PRIMARY KEY,
  `borrow_id` VARCHAR(50) NOT NULL,
  `fine_date` DATE NOT NULL,
  `days_late` INT NOT NULL DEFAULT 0,
  `amount` INT NOT NULL DEFAULT 0,
  CONSTRAINT `fk_fine_borrow` FOREIGN KEY (`borrow_id`) REFERENCES `Borrow`(`borrow_id`) ON UPDATE CASCADE ON DELETE CASCADE,
  UNIQUE KEY `uk_fine_borrow` (`borrow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OPTIONAL: Some baseline seed values
INSERT INTO `Role` (`role_id`, `role_name`) VALUES
  ('admin', 'Administrator')
ON DUPLICATE KEY UPDATE role_name = VALUES(role_name);

INSERT INTO `Permission` (`permission_id`, `permission_name`) VALUES
  ('manage_books', 'Quản lý sách'),
  ('manage_borrows', 'Quản lý mượn trả'),
  ('manage_readers', 'Quản lý độc giả')
ON DUPLICATE KEY UPDATE permission_name = VALUES(permission_name);

INSERT INTO `Role_Permission` (`role_id`, `permission_id`)
VALUES ('admin', 'manage_books'), ('admin', 'manage_borrows'), ('admin', 'manage_readers')
ON DUPLICATE KEY UPDATE role_id = VALUES(role_id);
