-- ============================================================
--  Pure PHP — Database Schema & Seed Data
--  Engine : InnoDB | Charset : utf8mb4 | Collation : unicode_ci
-- ============================================================

CREATE DATABASE IF NOT EXISTS `purephp`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `purephp`;

-- ── Roles ────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(60)      NOT NULL,
  `slug`        VARCHAR(60)      NOT NULL,
  `description` VARCHAR(255)     DEFAULT NULL,
  `color`       VARCHAR(20)      NOT NULL DEFAULT 'secondary',
  `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Permissions ──────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `permissions` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)     NOT NULL,
  `slug`        VARCHAR(100)     NOT NULL,
  `group_name`  VARCHAR(50)      DEFAULT 'general',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Role → Permission (pivot) ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id`       INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`)       REFERENCES `roles`(`id`)       ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Users ─────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(100)     NOT NULL,
  `email`         VARCHAR(150)     NOT NULL,
  `password_hash` VARCHAR(255)     NOT NULL,
  `role_id`       INT UNSIGNED     NOT NULL,
  `status`        ENUM('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `last_login`    TIMESTAMP        NULL DEFAULT NULL,
  `created_at`    TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  KEY `idx_users_status` (`status`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SEED DATA
-- ============================================================

-- ── Roles ────────────────────────────────────────────────────────────────────
INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `color`) VALUES
(1, 'Administrator', 'admin',     'Full access to all system features and settings.',      'primary'),
(2, 'Developer',     'developer', 'Access to code, deployments and technical settings.',   'info'),
(3, 'Designer',      'designer',  'Access to UI assets, components and style settings.',   'success'),
(4, 'Viewer',        'viewer',    'Read-only access to dashboards and public reports.',     'secondary');

-- ── Permissions ──────────────────────────────────────────────────────────────
INSERT INTO `permissions` (`id`, `name`, `slug`, `group_name`) VALUES
-- Users
(1,  'View Users',      'users.view',    'Users'),
(2,  'Create Users',    'users.create',  'Users'),
(3,  'Edit Users',      'users.edit',    'Users'),
(4,  'Delete Users',    'users.delete',  'Users'),
-- Roles
(5,  'View Roles',      'roles.view',    'Roles'),
(6,  'Create Roles',    'roles.create',  'Roles'),
(7,  'Edit Roles',      'roles.edit',    'Roles'),
(8,  'Delete Roles',    'roles.delete',  'Roles'),
-- Dashboard
(9,  'View Dashboard',  'dashboard.view','Dashboard'),
(10, 'View Reports',    'reports.view',  'Dashboard'),
-- Settings
(11, 'Manage Settings', 'settings.edit', 'Settings');

-- ── Role Permissions ──────────────────────────────────────────────────────────
-- Admin: all permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11);

-- Developer: users (no delete), dashboard, reports
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(2,1),(2,2),(2,3),(2,9),(2,10);

-- Designer: dashboard, reports
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(3,9),(3,10);

-- Viewer: dashboard only
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(4,9);

-- ── Users (passwords are all "password" → password_hash) ──────────────────────
-- To generate: password_hash('password', PASSWORD_BCRYPT)
INSERT INTO `users` (`name`, `email`, `password_hash`, `role_id`, `status`, `last_login`, `created_at`) VALUES
('Admin User',    'admin@demo.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'active',   '2025-04-11 09:14:00', '2024-01-12 10:00:00'),
('Alice Martin',  'alice@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'active',   '2025-04-10 18:22:00', '2024-01-12 10:00:00'),
('Bob Chen',      'bob@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-09 12:05:00', '2024-02-28 09:30:00'),
('Carol Diaz',    'carol@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'inactive', '2025-03-20 14:00:00', '2024-03-05 11:00:00'),
('David Kim',     'david@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'pending',  NULL,                  '2024-04-01 08:00:00'),
('Eva Müller',    'eva@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-11 07:55:00', '2024-04-10 15:20:00'),
('Frank Santos',  'frank@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-08 11:30:00', '2024-05-01 10:00:00'),
('Grace Lee',     'grace@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'active',   '2025-04-07 16:45:00', '2024-05-15 09:00:00'),
('Hiro Tanaka',   'hiro@mail.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'inactive', '2025-02-14 08:00:00', '2024-06-02 12:00:00'),
('Isabel Torres', 'isabel@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'active',   '2025-04-11 10:10:00', '2024-06-20 14:30:00'),
('James Wright',  'james@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'pending',  NULL,                  '2024-07-04 09:00:00'),
('Kate Brown',    'kate@mail.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-10 13:00:00', '2024-07-18 11:00:00'),
('Liam Nguyen',   'liam@mail.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'inactive', '2025-01-30 09:00:00', '2024-08-01 10:00:00'),
('Maria Rossi',   'maria@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'active',   '2025-04-09 15:20:00', '2024-08-15 12:00:00'),
('Nathan Osei',   'nathan@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-11 08:50:00', '2024-09-01 09:30:00'),
('Olivia Park',   'olivia@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'pending',  NULL,                  '2024-09-20 14:00:00'),
('Pedro Alves',   'pedro@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'active',   '2025-04-06 17:00:00', '2024-10-05 10:00:00'),
('Quinn Adams',   'quinn@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, 'active',   '2025-04-05 14:30:00', '2024-10-22 11:00:00'),
('Rachel Moore',  'rachel@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 'active',   '2025-04-10 09:45:00', '2024-11-01 09:00:00'),
('Samuel Clark',  'samuel@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2, 'inactive', '2025-03-01 12:00:00', '2024-11-18 10:00:00'),
('Tina Hoffman',  'tina@mail.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4, 'active',   '2025-04-04 16:00:00', '2024-12-03 08:30:00');

-- NOTE: All passwords above are hashed versions of the string "password"
-- (generated with password_hash('password', PASSWORD_BCRYPT))
-- Change them in production!
