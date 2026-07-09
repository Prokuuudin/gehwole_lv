-- Run once against a fresh MySQL/MariaDB database, e.g.:
--   mysql -u root -p gehwol_lv < php/migrations/001_init.sql
--
-- After running, create the first admin user:
--   1) generate a hash locally:
--      php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT), PHP_EOL;"
--   2) insert it:
--      INSERT INTO admin_users (username, password_hash) VALUES ('admin', '<paste hash here>');

CREATE TABLE categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  parent_id INT NULL,
  name VARCHAR(255) NOT NULL,
  link_url VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  FOREIGN KEY (parent_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  category_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT NULL,
  image VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE news (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  date DATE NULL,
  text TEXT NULL,
  image VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE articles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  title VARCHAR(255) NOT NULL,
  text TEXT NULL,
  image VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admin_users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  username VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (id, parent_id, name, link_url, sort_order) VALUES
(1,  NULL, 'Kosmētika', NULL, 1),
(2,  1,    'GEHWOL Classic', 'gehwol-classic.html', 1),
(3,  1,    'GEHWOL MED®', 'gehwol-med.html', 2),
(4,  1,    'GEHWOL FUSSKRAFT', 'gehwol-fusskraft.html', 3),
(5,  1,    'GEHWOL FUSSKRAFT Soft Feet', 'gehwol-fusskraft-soft-feet.html', 4),
(6,  1,    'GEHWOL PROFESSIONAL', 'gehwol-professional.html', 5),
(7,  1,    'GERLASAN', 'gerlasan.html', 6),
(8,  1,    'GERLAVIT', 'gerlavit.html', 7),
(9,  NULL, 'Spiedienu uz pēdām mazinoši līdzekļi', NULL, 2),
(10, 9,    'Polimēra gēla izstrādājumi, pārvilkti ar tekstilu', 'polimera-gela-izstradajumi-parvilkti-ar-tekstilu.html', 1),
(11, 9,    'Polimēra gēla izstrādājumi', 'polimera-gela-izstradajumi.html', 2),
(12, 9,    'Plāksteri', 'plaksteri.html', 3),
(13, 9,    'Filca izstrādājumi', 'filca-izstradajumi.html', 4),
(14, NULL, 'Tehnika', NULL, 3),
(15, 14,   'Pēdu kopšanas aparāti', 'pedu-kopsanas-aparati.html', 1),
(16, 14,   'Pacienta krēsli', 'pacienta-kresli.html', 2),
(17, 14,   'Darbinieka krēsli', 'darbinieka-kresli.html', 3),
(18, 14,   'Rotējošie instrumenti', NULL, 4),
(19, 18,   'Keramiskās frēzes', 'keramiskas-frezes.html', 1),
(20, 18,   'Pulētāji', 'puletaji.html', 2),
(21, 18,   'Vienreizlietojamās smilšpapīra frēzes', 'vienreizlietojamas-smilspapira-frezes.html', 3);

ALTER TABLE categories AUTO_INCREMENT = 22;
