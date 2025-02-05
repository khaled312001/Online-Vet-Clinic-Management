
-- Users Table (Admins, Doctors, Users)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(191) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL, -- SHA256 hashed passwords
    role ENUM('admin', 'doctor', 'user') NOT NULL,
    phone VARCHAR(15) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT 'default-avatar.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Pets Table
CREATE TABLE IF NOT EXISTS pets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    breed VARCHAR(255) DEFAULT NULL,
    type ENUM('dog', 'cat', 'bird', 'other') NOT NULL,
    age INT NOT NULL,
    health_status TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Appointments Table
CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT NOT NULL,
    doctor_id INT NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'canceled') DEFAULT 'pending',
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Vaccinations Table
CREATE TABLE IF NOT EXISTS vaccinations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pet_id INT NOT NULL,
    vaccine_name VARCHAR(255) NOT NULL,
    date_given DATE NOT NULL,
    next_due DATE NOT NULL,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Emergency Requests Table
CREATE TABLE IF NOT EXISTS emergencies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Consultations Table
CREATE TABLE IF NOT EXISTS consultations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    doctor_id INT NOT NULL,
    notes TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Prescriptions Table
CREATE TABLE IF NOT EXISTS prescriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    pet_id INT NOT NULL,
    medicine VARCHAR(255) NOT NULL,
    dosage VARCHAR(255) NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (pet_id) REFERENCES pets(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Forum Posts Table
CREATE TABLE IF NOT EXISTS forum_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Forum Likes Table
CREATE TABLE IF NOT EXISTS forum_likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE(post_id, user_id) -- Prevents duplicate likes from the same user
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Forum Comments Table
CREATE TABLE IF NOT EXISTS forum_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES forum_posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data for Testing

-- Admin User
INSERT INTO users (name, email, password, role, phone) VALUES 
('Admin User', 'admin@petcare.com', SHA2('admin123', 256), 'admin', '1234567890');

-- Doctors
INSERT INTO users (name, email, password, role, phone) VALUES 
('Dr. Sarah Johnson', 'sarah@petcare.com', SHA2('doctor123', 256), 'doctor', '1234567891'),
('Dr. Michael Brown', 'michael@petcare.com', SHA2('doctor123', 256), 'doctor', '1234567892');

-- Users
INSERT INTO users (name, email, password, role, phone) VALUES 
('John Doe', 'john@petcare.com', SHA2('user123', 256), 'user', '1234567893'),
('Jane Smith', 'jane@petcare.com', SHA2('user123', 256), 'user', '1234567894');

-- Pets
INSERT INTO pets (user_id, name, breed, type, age, health_status) VALUES
(3, 'Buddy', 'Golden Retriever', 'dog', 3, 'Healthy'),
(4, 'Whiskers', 'Persian Cat', 'cat', 2, 'Needs vaccination');

-- Appointments
INSERT INTO appointments (pet_id, doctor_id, date, time, status) VALUES
(1, 2, '2025-02-10', '10:00:00', 'pending'),
(2, 3, '2025-02-12', '14:00:00', 'pending');

-- Vaccinations
INSERT INTO vaccinations (pet_id, vaccine_name, date_given, next_due) VALUES
(1, 'Rabies', '2025-01-01', '2025-07-01'),
(2, 'Feline Distemper', '2025-01-15', '2025-07-15');

-- Emergency Requests
INSERT INTO emergencies (user_id, message, status) VALUES
(3, 'My dog is vomiting continuously.', 'pending');

-- Consultations
INSERT INTO consultations (appointment_id, doctor_id, notes) VALUES
(1, 2, 'The dog has a mild fever, prescribed antibiotics.');

-- Prescriptions
INSERT INTO prescriptions (doctor_id, pet_id, medicine, dosage) VALUES
(2, 1, 'Amoxicillin', '5 ml twice daily for 7 days');

-- Forum Posts
INSERT INTO forum_posts (user_id, title, content) VALUES
(3, 'Best Food for Dogs', 'What is the best food brand for dogs?'),
(4, 'Cat Vaccination', 'Can anyone suggest a good vaccination schedule for cats?');

-- Forum Comments
INSERT INTO forum_comments (post_id, user_id, content) VALUES
(1, 6, 'Hill’s Science Diet Adult Chicken & Barley Recipe'),
(1, 6, 'Royal Canin Health Nutrition Adult Dry Dog Food');

-- Forum Likes
INSERT INTO forum_likes (post_id, user_id) VALUES
(1, 6);

ALTER TABLE consultations ADD COLUMN reply TEXT NULL AFTER notes;
