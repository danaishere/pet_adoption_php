

-- TABLES
-- PET PROFILE -- 
CREATE TABLE pet_profiles (
pet_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(100) NOT NULL,
species VARCHAR(50) NOT NULL,
breed VARCHAR(100),
age_years INT DEFAULT 0,
age_months INT DEFAULT 0,
gender ENUM('Male', 'Female'),
color VARCHAR(100),
weight_kg DECIMAL(5,2),
photo VARCHAR(255) DEFAULT NULL,
adoption_status ENUM('Available', 'Pending', 'Adopted') DEFAULT 'Available',
date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
description TEXT
);



-- SAMPLE DATA --
INSERT INTO pet_profiles (name, species, breed, age_years, age_months, gender, color, weight_kg, photo, adoption_status, description) VALUES
('Bear', 'Dog', 'Labrador Retriever', 5, 2, 'Male', 'Black', 30.3, 'uploads/pets/bear.jpg', 'Available', 'Friendly lovely little buddy. Loves to play fetch and go for walks.'),
('Luna', 'Cat', 'Persian', 9, 0, 'Female', 'White', 4.2, 'uploads/pets/luna.jpg', 'Available', 'Luna is a calm and affectionate Persian who enjoys lounging and being petted.'),
('Coco', 'Rabbit', 'Holland Lop', 1, 8, 'Female', 'Black and White', 1.8, 'uploads/pets/coco.jpg', 'Available', 'Coco is a sweet and gentle bunny, perfect for a quiet home.'),
('Nala', 'Cat', 'Maine Coon', 3, 4, 'Female', 'Tabby', 5.9, 'uploads/pets/nala.jpg', 'Pending', 'Nala is a very demanding queen who needs someone to respect her personal space. She might have some attitude but has some soft spot for a good butt scratch.'),
('Rocky', 'Dog', 'Bulldog', 4, 2, 'Male', 'Brindle', 24.0, 'uploads/pets/rocky.jpg', 'Adopted', 'Rocky is a gentle and laid-back Bulldog who loves naps and short walks.');
