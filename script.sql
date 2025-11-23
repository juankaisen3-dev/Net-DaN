CREATE DATABASE IF NOT EXISTS netverse;
USE netverse;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    avatar VARCHAR(500) DEFAULT 'https://via.placeholder.com/150',
    subscription ENUM('basic', 'premium', 'family') DEFAULT 'basic',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS videos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    video_url VARCHAR(500) NOT NULL,
    thumbnail VARCHAR(500) NOT NULL,
    duration INT,
    category_id INT,
    genre VARCHAR(100),
    release_year INT,
    rating DECIMAL(2,1),
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Insérer des catégories
INSERT IGNORE INTO categories (id, name, description) VALUES 
(1, 'Films', 'Longs métrages et films'),
(2, 'Séries', 'Séries télévisées'),
(3, 'Documentaires', 'Films documentaires'),
(4, 'Animations', 'Contenu animé');

-- Insérer des vidéos d'exemple
INSERT IGNORE INTO videos (id, title, description, video_url, thumbnail, duration, category_id, genre, release_year, rating, is_featured) VALUES
(1, 'Inception', 'Un voleur qui entre dans les rêves', 'https://example.com/video1.mp4', 'https://via.placeholder.com/300x450/000000/FFFFFF?text=Inception', 148, 1, 'Science-Fiction', 2010, 8.8, true),
(2, 'The Dark Knight', 'Batman contre le Joker', 'https://example.com/video2.mp4', 'https://via.placeholder.com/300x450/000000/FFFFFF?text=The+Dark+Knight', 152, 1, 'Action', 2008, 9.0, true),
(3, 'Stranger Things', 'Série de science-fiction horrifique', 'https://example.com/video3.mp4', 'https://via.placeholder.com/300x450/000000/FFFFFF?text=Stranger+Things', 60, 2, 'Horreur', 2016, 8.7, true);