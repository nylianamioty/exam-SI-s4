drop database if exists exam_s4;

CREATE DATABASE exam_s4 CHARACTER SET utf8mb4;

USE exam_s4;

-- Tables 
CREATE TABLE client (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(100),
    age INT
);

CREATE TABLE fonds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(50),
    montant DECIMAL(10,2) NOT NULL,
    date_ajout DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS type_pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    taux DECIMAL(5,2) NOT NULL
);

CREATE TABLE IF NOT EXISTS pret (
    id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    type_pret_id INT NOT NULL,
    fond_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status VARCHAR(50) DEFAULT 'active',
    FOREIGN KEY (fond_id) REFERENCES fonds(id)
);
CREATE TABLE IF NOT EXISTS calculs_interets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    periode CHAR(7) NOT NULL,  -- Format YYYY-MM
    interet_total DECIMAL(12,2) NOT NULL,
    date_calcul TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE VIEW interets_mensuels AS
SELECT 
    DATE_FORMAT(p.start_date, '%Y-%m') AS periode,
    SUM(p.amount * tp.taux / 100 / 12) AS interet_mensuel,
    COUNT(p.id) AS nombre_prets,
    SUM(p.amount) AS montant_total
FROM 
    pret p
JOIN 
    type_pret tp ON p.type_pret_id = tp.id
WHERE 
    p.status = 'active'
GROUP BY 
    DATE_FORMAT(p.start_date, '%Y-%m');

-- Donnees
-- Client
INSERT INTO client (nom, prenom, email, age) VALUES
('Dupont', 'Jean', 'jean.dupont@email.com', 22),
('Martin', 'Claire', 'claire.martin@email.com', 24),
('Nguyen', 'Paul', 'paul.nguyen@email.com', 21);

-- Fonds
INSERT INTO fonds (description, montant, date_ajout) VALUES
('Fonds principal', 10000.00, '2024-06-01 10:00:00'),
('Fonds secondaire', 5000.00, '2024-06-10 14:30:00'),
('Fonds d urgence', 2000.00, '2024-07-01 09:15:00');

-- Types de prêt
INSERT INTO type_pret (nom, taux) VALUES
('Prêt personnel', 3.50),
('Prêt immobilier', 1.80),
('Prêt étudiant', 0.90);

-- Prêts clients
INSERT INTO pret (client_id, type_pret_id, fond_id, amount, start_date, end_date, status) VALUES
(1, 1, 1, 2000.00, '2024-07-01', '2025-07-01', 'active'),
(2, 2, 2, 3000.00, '2024-06-15', '2029-06-15', 'active'),
(3, 3, 3, 1500.00, '2024-07-05', NULL, 'active');
