CREATE DATABASE IF NOT EXISTS kringloop_centrum;
USE kringloop_centrum;

CREATE TABLE IF NOT EXISTS categorie (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL,
    omschrijving TEXT
);

CREATE TABLE IF NOT EXISTS voorraad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    omschrijving VARCHAR(255) NOT NULL,
    hoeveelheid INT NOT NULL DEFAULT 0
);

-- Sample data
INSERT INTO categorie (code, omschrijving) VALUES ('Scho', 'Schoenen');
INSERT INTO voorraad (omschrijving, hoeveelheid) VALUES 
('T-shirt', 5),
('Jeans', 7),
('Sokken', 10),
('Muts', 3);
