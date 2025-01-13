CREATE DATABASE  Youdemy ;

use Youdemy ;

-- Table des utilisateurs
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Mot de passe haché
    role ENUM('Student', 'Teacher', 'Administrator') NOT NULL DEFAULT 'Student',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des catégories de cours
CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Table des cours
CREATE TABLE courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    content TEXT, 
    category_id INT,
    teacher_id INT NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(category_id),
    FOREIGN KEY (teacher_id) REFERENCES users(user_id)
);

-- Table des inscriptions des étudiants aux cours
CREATE TABLE enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    student_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id)
);

-- Table des tags
CREATE TABLE tags (
    tag_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);

-- Table pivot pour les relations many-to-many entre cours et tags
CREATE TABLE course_tags (
    course_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (course_id, tag_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id),
    FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
);

INSERT INTO users (name, email, password, role) VALUES
('John Doe', 'john.doe@example.com', 'hashed_password1', 'Student'),
('Jane Smith', 'jane.smith@example.com', 'hashed_password2', 'Teacher'),
('Alice Johnson', 'alice.johnson@example.com', 'hashed_password3', 'Student'),
('Bob Williams', 'bob.williams@example.com', 'hashed_password4', 'Teacher'),
('Admin User', 'admin@example.com', 'hashed_password5', 'Administrator');


INSERT INTO categories (name) VALUES
('Programming'),
('Design'),
('Business'),
('Marketing'),
('Mathematics'),
('Science');


INSERT INTO courses (title, description, content, category_id, teacher_id) VALUES
('Learn JavaScript', 'An introduction to JavaScript programming.', 'video_url_or_content1', 1, 2),
('UI/UX Design Basics', 'Foundations of user interface and user experience design.', 'video_url_or_content2', 2, 4),
('Entrepreneurship 101', 'How to start your own business.', 'video_url_or_content3', 3, 2),
('Digital Marketing Strategies', 'Advanced digital marketing tactics and strategies.', 'video_url_or_content4', 4, 4);


INSERT INTO tags (name) VALUES
('JavaScript'),
('CSS'),
('Entrepreneurship'),
('SEO'),
('Data Science'),
('Machine Learning'),
('ReactJS'),
('Graphic Design');

 INSERT INTO course_tags (course_id, tag_id) VALUES
(1, 1), -- "Learn JavaScript" -> "JavaScript"
(1, 7), -- "Learn JavaScript" -> "ReactJS"
(2, 2), -- "UI/UX Design Basics" -> "CSS"
(2, 8), -- "UI/UX Design Basics" -> "Graphic Design"
(3, 3), -- "Entrepreneurship 101" -> "Entrepreneurship"
(4, 4); -- "Digital Marketing Strategies" -> "SEO"

INSERT INTO enrollments (course_id, student_id) VALUES
(1, 1), -- John Doe inscrit à "Learn JavaScript"
(2, 3), -- Alice Johnson inscrit à "UI/UX Design Basics"
(3, 1), -- John Doe inscrit à "Entrepreneurship 101"
(4, 3); -- Alice Johnson inscrit à "Digital Marketing Strategies"
