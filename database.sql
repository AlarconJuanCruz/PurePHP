-- ============================================================
--  Pure PHP — Database Schema & Seed Data
--  Engine : InnoDB | Charset : utf8mb4
-- ============================================================

CREATE DATABASE IF NOT EXISTS `purephp`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `purephp`;

-- ── Roles ──────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `roles` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(60)  NOT NULL,
  `slug`        VARCHAR(60)  NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `color`       VARCHAR(20)  NOT NULL DEFAULT 'secondary',
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_roles_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Permissions ────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `permissions` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100) NOT NULL,
  `slug`       VARCHAR(100) NOT NULL,
  `group_name` VARCHAR(50)  DEFAULT 'general',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_permissions_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Role ↔ Permission ──────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `role_id`       INT UNSIGNED NOT NULL,
  `permission_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`)       REFERENCES `roles`(`id`)       ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Users ──────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(100) NOT NULL,
  `email`         VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role_id`       INT UNSIGNED NOT NULL,
  `status`        ENUM('active','inactive','pending') NOT NULL DEFAULT 'pending',
  `last_login`    TIMESTAMP NULL DEFAULT NULL,
  `created_at`    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role_id`),
  KEY `idx_users_status` (`status`),
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Categorias ─────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `categorias` (
  `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(100) NOT NULL,
  `slug`        VARCHAR(100) NOT NULL,
  `descripcion` VARCHAR(255) DEFAULT NULL,
  `color`       VARCHAR(20)  NOT NULL DEFAULT 'primary',
  `orden`       INT          NOT NULL DEFAULT 0,
  `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categorias_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Notas (Articles) ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `notas` (
  `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titulo`          VARCHAR(255) NOT NULL,
  `subtitulo`       VARCHAR(255) DEFAULT NULL,
  `slug`            VARCHAR(255) NOT NULL,
  `cuerpo`          LONGTEXT     DEFAULT NULL,
  `extracto`        TEXT         DEFAULT NULL,
  `imagen_portada`  VARCHAR(255) DEFAULT NULL,
  `estado`          ENUM('borrador','publicado','archivado') NOT NULL DEFAULT 'borrador',
  `destacada`       TINYINT(1)   NOT NULL DEFAULT 0,
  `categoria_id`    INT UNSIGNED DEFAULT NULL,
  `user_id`         INT UNSIGNED NOT NULL,
  `views`           INT UNSIGNED NOT NULL DEFAULT 0,
  `published_at`    TIMESTAMP NULL DEFAULT NULL,
  `created_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_notas_slug` (`slug`),
  KEY `idx_notas_estado` (`estado`),
  KEY `idx_notas_categoria` (`categoria_id`),
  KEY `idx_notas_user` (`user_id`),
  KEY `idx_notas_published` (`published_at`),
  FOREIGN KEY (`categoria_id`) REFERENCES `categorias`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`user_id`)      REFERENCES `users`(`id`)      ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Imagenes de notas (gallery) ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `nota_imagenes` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nota_id`    INT UNSIGNED NOT NULL,
  `archivo`    VARCHAR(255) NOT NULL,
  `titulo`     VARCHAR(255) DEFAULT NULL,
  `orden`      INT          NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_nota_imagenes_nota` (`nota_id`),
  FOREIGN KEY (`nota_id`) REFERENCES `notas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Site settings ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `key`   VARCHAR(100) NOT NULL,
  `value` TEXT         DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  SEED DATA
-- ============================================================

-- ── Roles ──────────────────────────────────────────────────────────────────
INSERT INTO `roles` (`id`,`name`,`slug`,`description`,`color`) VALUES
(1,'Administrator','admin',    'Full access to all features.',               'primary'),
(2,'Editor',       'editor',   'Can create, edit and publish articles.',      'info'),
(3,'Author',       'author',   'Can create and edit own articles.',           'success'),
(4,'Viewer',       'viewer',   'Read-only access to dashboard and reports.',  'secondary');

-- ── Permissions ────────────────────────────────────────────────────────────
INSERT INTO `permissions` (`id`,`name`,`slug`,`group_name`) VALUES
(1, 'View Users',        'users.view',      'Users'),
(2, 'Create Users',      'users.create',    'Users'),
(3, 'Edit Users',        'users.edit',      'Users'),
(4, 'Delete Users',      'users.delete',    'Users'),
(5, 'View Roles',        'roles.view',      'Roles'),
(6, 'Manage Roles',      'roles.manage',    'Roles'),
(7, 'View Dashboard',    'dashboard.view',  'Dashboard'),
(8, 'View Notas',        'notas.view',      'Notas'),
(9, 'Create Notas',      'notas.create',    'Notas'),
(10,'Edit Notas',        'notas.edit',      'Notas'),
(11,'Delete Notas',      'notas.delete',    'Notas'),
(12,'Publish Notas',     'notas.publish',   'Notas'),
(13,'Manage Categories', 'categorias.manage','Notas');

-- ── Role permissions ───────────────────────────────────────────────────────
-- Admin: all
INSERT INTO `role_permissions` VALUES
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),(1,11),(1,12),(1,13);
-- Editor: dashboard + all notas
INSERT INTO `role_permissions` VALUES
(2,7),(2,8),(2,9),(2,10),(2,11),(2,12),(2,13);
-- Author: dashboard + create/edit own notas
INSERT INTO `role_permissions` VALUES
(3,7),(3,8),(3,9),(3,10);
-- Viewer: dashboard only
INSERT INTO `role_permissions` VALUES
(4,7);

-- ── Categorias ─────────────────────────────────────────────────────────────
INSERT INTO `categorias` (`nombre`,`slug`,`descripcion`,`color`,`orden`) VALUES
('Política',       'politica',       'Noticias políticas nacionales e internacionales.', 'danger',    1),
('Economía',       'economia',       'Finanzas, mercados y economía argentina.',          'success',   2),
('Tecnología',     'tecnologia',     'Tech, startups e innovación.',                     'primary',   3),
('Deportes',       'deportes',       'Fútbol, tenis y deportes nacionales.',             'info',      4),
('Cultura',        'cultura',        'Arte, cine, música y espectáculos.',               'warning',   5),
('Sociedad',       'sociedad',       'Temas sociales, educación y salud.',               'secondary', 6);

-- ── Users (password = "password") ─────────────────────────────────────────
INSERT INTO `users` (`name`,`email`,`password_hash`,`role_id`,`status`,`last_login`,`created_at`) VALUES
('Admin User',   'admin@demo.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,'active','2025-04-11 09:14:00','2024-01-12 10:00:00'),
('Alice Martin', 'alice@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,'active','2025-04-10 18:22:00','2024-01-12 10:00:00'),
('Bob Chen',     'bob@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2,'active','2025-04-09 12:05:00','2024-02-28 09:30:00'),
('Carol Diaz',   'carol@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3,'inactive','2025-03-20 14:00:00','2024-03-05 11:00:00'),
('David Kim',    'david@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 4,'pending', NULL,'2024-04-01 08:00:00'),
('Eva Müller',   'eva@mail.com',    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2,'active','2025-04-11 07:55:00','2024-04-10 15:20:00'),
('Frank Santos', 'frank@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3,'active','2025-04-08 11:30:00','2024-05-01 10:00:00'),
('Grace Lee',    'grace@mail.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3,'active','2025-04-07 16:45:00','2024-05-15 09:00:00'),
('Isabel Torres','isabel@mail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1,'active','2025-04-11 10:10:00','2024-06-20 14:30:00'),
('Kate Brown',   'kate@mail.com',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2,'active','2025-04-10 13:00:00','2024-07-18 11:00:00');

-- ── Site settings ──────────────────────────────────────────────────────────
INSERT INTO `settings` (`key`,`value`) VALUES
('site_name',        'Mi Portal de Noticias'),
('site_tagline',     'Las noticias más importantes de Argentina'),
('site_description', 'Portal de noticias independiente con información actualizada.'),
('notas_per_page',   '12'),
('show_author',      '1'),
('show_date',        '1');

-- ── Notas de ejemplo ───────────────────────────────────────────────────────
INSERT INTO `notas` (`titulo`,`subtitulo`,`slug`,`cuerpo`,`extracto`,`estado`,`destacada`,`categoria_id`,`user_id`,`published_at`,`created_at`) VALUES
('El gobierno anuncia nuevo plan económico para 2025',
 'Medidas de estabilización fiscal y reducción del déficit',
 'gobierno-plan-economico-2025',
 '<p>El gobierno nacional presentó hoy un ambicioso plan económico que busca estabilizar las finanzas públicas durante el año en curso. Las medidas incluyen una reducción gradual del déficit fiscal, ajustes en el tipo de cambio y nuevos incentivos para la inversión privada.</p><p>El ministro de economía detalló que el plan contempla tres ejes fundamentales: control de la inflación, reactivación del consumo interno y atracción de inversiones extranjeras directas.</p><p>Los mercados financieros reaccionaron positivamente a las noticias, con el índice bursátil local subiendo un 3.2% en las primeras horas de la jornada.</p>',
 'El gobierno presentó un plan económico con medidas de estabilización fiscal y nuevos incentivos para la inversión privada.',
 'publicado',1,2,1,'2025-04-10 08:00:00','2025-04-10 07:30:00'),

('Selección argentina: convocatoria para las eliminatorias',
 'El DT confirmó la lista de 26 jugadores para los próximos partidos',
 'seleccion-convocatoria-eliminatorias',
 '<p>El director técnico de la selección argentina dio a conocer hoy la lista de convocados para los próximos dos partidos de las eliminatorias sudamericanas rumbo al Mundial.</p><p>Entre las novedades se destaca el regreso de jugadores que habían estado fuera por lesión y la inclusión de dos jóvenes promesas del fútbol local que disputarán su primera convocatoria a la selección mayor.</p><p>Los partidos se disputarán el próximo mes en el estadio Monumental ante el combinado nacional.</p>',
 'El DT confirmó la lista de 26 jugadores para los próximos partidos de las eliminatorias.',
 'publicado',1,4,1,'2025-04-09 10:00:00','2025-04-09 09:00:00'),

('Argentina lidera el ranking latinoamericano de innovación tech',
 'Informe destaca el crecimiento del ecosistema de startups',
 'argentina-ranking-innovacion-tech',
 '<p>Un informe elaborado por una consultora internacional ubicó a Argentina en el primer lugar del ranking latinoamericano de innovación tecnológica, superando a Brasil y México en varios indicadores clave.</p><p>El ecosistema de startups argentino creció un 45% durante el último año, con más de 3.000 nuevas empresas de base tecnológica registradas. Las áreas de mayor crecimiento fueron fintech, agtech y healthtech.</p>',
 'Argentina lidera el ranking latinoamericano de innovación tecnológica según informe internacional.',
 'publicado',0,3,2,'2025-04-08 14:00:00','2025-04-08 13:00:00'),

('Nuevo récord en la Feria del Libro de Buenos Aires',
 'Más de 1.2 millones de visitantes en la edición 2025',
 'feria-libro-buenos-aires-record',
 '<p>La 49ª edición de la Feria Internacional del Libro de Buenos Aires cerró sus puertas con un nuevo récord histórico: más de 1.2 millones de visitantes en sus 18 días de duración.</p><p>El evento, considerado el más importante del género en América Latina, contó con la participación de más de 1.400 expositores de 40 países y más de 2.000 actividades culturales entre presentaciones, charlas y talleres.</p>',
 'La Feria Internacional del Libro de Buenos Aires cerró con récord de 1.2 millones de visitantes.',
 'publicado',0,5,3,'2025-04-07 18:00:00','2025-04-07 17:00:00'),

('Reforma educativa: cambios en el plan de estudios secundario',
 'El ministerio presentó el nuevo diseño curricular para el ciclo 2025',
 'reforma-educativa-plan-estudios',
 '<p>El Ministerio de Educación presentó hoy los lineamientos del nuevo diseño curricular para el nivel secundario que comenzará a implementarse de forma gradual a partir del ciclo lectivo siguiente.</p><p>Entre los cambios más relevantes se incluye la incorporación de educación financiera, pensamiento computacional y habilidades socioemocionales como materias obligatorias en todos los años del secundario.</p>',
 'El Ministerio de Educación presentó el nuevo diseño curricular con incorporación de educación financiera y pensamiento computacional.',
 'publicado',0,6,1,'2025-04-06 09:00:00','2025-04-06 08:00:00'),

('Draft: Análisis del presupuesto nacional 2026',
 'Una mirada detallada a los números del proyecto',
 'analisis-presupuesto-2026',
 '<p>Contenido en elaboración...</p>',
 '',
 'borrador',0,2,2,NULL,'2025-04-11 10:00:00');

-- ── Imagen portada de ejemplo (usar la misma imagen placeholder) ────────────
-- (Las imágenes reales se suben desde el panel de administración)
