DROP DATABASE IF EXISTS lemalagasy_db;
CREATE DATABASE lemalagasy_db;
\c lemalagasy_db;

-- Utilisateurs
CREATE TABLE role (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL -- admin, journalist, editeur, superjournalist
);

CREATE TABLE utilisateur (
    id SERIAL PRIMARY KEY,
    id_role INT REFERENCES role(id),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    date_creation TIMESTAMP DEFAULT NOW()
);

-- Catégories
CREATE TABLE categorie (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Tags
CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE article (
    id SERIAL PRIMARY KEY,
    title TEXT,
    summary TEXT,
    mongodb_id VARCHAR(50),      -- Reference to MongoDB content
    published_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE article_categories (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES article(id),
    id_category INT REFERENCES categorie(id)
);

CREATE TABLE article_tags (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES article(id),
    id_tag INT REFERENCES tag(id)
);

CREATE TABLE article_authors (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES article(id),
    id_utilisateur INT REFERENCES utilisateur(id)
);

CREATE TABLE article_images (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES article(id),
    path TEXT NOT NULL
); 

CREATE TABLE category_featured_articles (
    id SERIAL PRIMARY KEY,
    id_category INT NOT NULL REFERENCES categorie(id) ON DELETE CASCADE,
    id_article INT NOT NULL REFERENCES article(id) ON DELETE CASCADE,
    display_order INT NOT NULL DEFAULT 1,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_category_featured_order UNIQUE (id_category, display_order),
    CONSTRAINT uq_category_featured_article UNIQUE (id_category, id_article)
);

CREATE INDEX idx_category_featured_active_order
    ON category_featured_articles (id_category, is_active, display_order);

CREATE TYPE feed_slot AS ENUM ('FEATURED', 'LATEST', 'SPOTLIGHT');
CREATE TABLE home_feed (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES article(id) ON DELETE CASCADE,
    slot feed_slot NOT NULL,
    display_order INT NOT NULL DEFAULT 1,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    starts_at TIMESTAMP,
    ends_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT uq_home_feed_slot_order UNIQUE (slot, display_order),
    CONSTRAINT uq_home_feed_slot_article UNIQUE (slot, id_article)
);

CREATE INDEX idx_home_feed_active_slot_order ON home_feed (is_active, slot, display_order);

CREATE TYPE statut_article AS ENUM ('BROUILLON','PUBLIE','ARCHIVE');
CREATE TABLE article_status (
    id SERIAL PRIMARY KEY,
    statut statut_article NOT NULL DEFAULT 'BROUILLON',
    id_article INT REFERENCES article(id)
);

INSERT INTO role (name) VALUES
('admin'),
('journalist'),
('editor'),
('superjournalist');

INSERT INTO utilisateur (id_role, name, email) VALUES
(1, 'Rado Mihaja', 'rado.mihaja@lemalagasy.com'),
(2, 'Patrick Randria', 'patrick.randria@lemalagasy.com'),
(2, 'Sanda Rakoto', 'sanda.rakoto@lemalagasy.com'),
(3, 'Paul Rabary', 'paul.rabary@lemalagasy.com'),
(4, 'David Andrianina', 'david.andrianina@lemalagasy.com');

INSERT INTO categorie (name) VALUES
('International'),
('Guerre'),
('Politique'),
('Economie'),
('Analyse'),
('Diplomatie'),
('Sécurité');

INSERT INTO tag (name) VALUES
('Iran'),
('Conflit'),
('Moyen-Orient'),
('Israël'),
('États-Unis'),
('Sanctions'),
('Nucléaire'),
('Tensions'),
('Armée'),
('Diplomatie'),
('ONU'),
('Pétrole');