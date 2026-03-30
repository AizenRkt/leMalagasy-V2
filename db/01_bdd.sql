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

CREATE TYPE statut_article AS ENUM ('BROUILLON','PUBLIE','ARCHIVE');
CREATE TABLE article_status (
    id SERIAL PRIMARY KEY,
    statut statut_article NOT NULL DEFAULT 'BROUILLON',
    id_article INT REFERENCES article(id)
);
