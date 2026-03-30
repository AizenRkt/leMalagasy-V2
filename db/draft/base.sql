DROP DATABASE IF EXISTS lemalagasy_db;
CREATE DATABASE lemalagasy_db;
\c lemalagasy_db;

--admin, utilisateurs, journalistes,etc.--
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password_hash TEXT,
    id_role INT REFERENCES roles(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

--status: brouillon, publié, archivé, etc.--
CREATE TABLE status (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) UNIQUE
);

--politique, sport, culture, etc.--
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE
);

--guerre, Iran ,Crise, etc.-- 
CREATE TABLE tags (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE
);

CREATE TABLE articles (
    id SERIAL PRIMARY KEY,
    title TEXT,
    summary TEXT,
    id_user INT REFERENCES users(id),
    status VARCHAR(20), 
    id_category INT REFERENCES categories(id),
    published_at TIMESTAMP,
    id_mongo_content VARCHAR(50), 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE article_tags (
    id SERIAL PRIMARY KEY,
    id_article INT REFERENCES articles(id),
    id_tag INT REFERENCES tags(id)
);

--visible, caché, etc.--
CREATE TABLE status_comments (
    id SERIAL PRIMARY KEY,
    name VARCHAR(20) UNIQUE
);

CREATE TABLE comments (
    id SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id),
    id_article INT REFERENCES articles(id),
    id_parent INT NULL, -- reponse commentaire
    content TEXT,
    id_status_comment INT REFERENCES status_comments(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO roles (name) VALUES
('admin'),
('journalist'),
('reader');

INSERT INTO users (username, email, password_hash, id_role) VALUES
('admin1', 'admin@mail.com', 'hashed_pwd', 1),
('journalist1', 'j1@mail.com', 'hashed_pwd', 2),
('journalist2', 'j2@mail.com', 'hashed_pwd', 2),
('reader1', 'r1@mail.com', 'hashed_pwd', 3),
('reader2', 'r2@mail.com', 'hashed_pwd', 3);

INSERT INTO status (name) VALUES
('draft'),
('published'),
('archived');

INSERT INTO categories (name) VALUES
('Politics'),
('Military'),
('Economy'),
('Diplomacy'),
('International'),
('Humanitarian'),
('Technology');

INSERT INTO tags (name) VALUES
('Iran'),
('USA'),
('Israel'),
('Missile'),
('Drone'),
('Sanctions'),
('Oil'),
('Ceasefire'),
('Tehran'),
('Middle East');

INSERT INTO status_comments (name) VALUES
('visible'),
('hidden'),
('deleted');