-- Campus Lost & Found Database Setup Script
-- Execute this file in phpMyAdmin or MySQL command line

-- Create Database
CREATE DATABASE IF NOT EXISTS campus_lost_found;
USE campus_lost_found;

-- Create Items Table
CREATE TABLE IF NOT EXISTS items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    item_type ENUM('Lost', 'Found') NOT NULL,
    date_reported DATE NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    contact_info VARCHAR(255) NOT NULL,
    image LONGBLOB,
    mime_type VARCHAR(50),
    status ENUM('Pending', 'Claimed', 'Returned') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Sample Data (Optional)
INSERT INTO items (item_name, category, item_type, date_reported, location, description, contact_info, status) VALUES
('Apple AirPods Pro', 'Electronics', 'Lost', '2026-05-18', 'Library Building - 2nd Floor', 'Lost my Apple AirPods Pro in white color near the study area. Still has the charging case.', 'john@student.edu | +1234567890', 'Pending'),
('Brown Leather Wallet', 'Accessories', 'Lost', '2026-05-17', 'Cafeteria', 'Lost brown leather wallet with student ID and some cash. Very important documents inside.', 'sarah@student.edu | +1234567891', 'Pending'),
('College Textbook - Chemistry', 'Books', 'Found', '2026-05-16', 'Basketball Court', 'Found a Chemistry textbook near the basketball court. Has a name tag inside. Semester 2.', 'admin@campus.edu | +1234567892', 'Pending'),
('Blue Nike Backpack', 'Clothing', 'Lost', '2026-05-15', 'Student Parking Lot', 'Blue Nike backpack with laptop inside and important notes for exams. Loss near parking lot.', 'mike@student.edu | +1234567893', 'Claimed'),
('Student ID Card - 2024', 'ID Cards', 'Found', '2026-05-14', 'Admission Office', 'Found a student ID card in the admission office. Name visible on card.', 'admin@campus.edu | +1234567894', 'Returned'),
('Silver Watch - Fossil', 'Accessories', 'Lost', '2026-05-12', 'Computer Lab - Room 301', 'Lost my silver Fossil watch in the computer lab. Sentimental value.', 'emma@student.edu | +1234567895', 'Pending'),
('Notebook Set', 'School Supplies', 'Found', '2026-05-11', 'Lecture Hall A', 'Found a set of notebooks with notes in Lecture Hall A. Owner details on the cover.', 'admin@campus.edu | +1234567896', 'Pending'),
('Black Umbrella', 'Accessories', 'Lost', '2026-05-10', 'Main Gate', 'Lost black umbrella at main gate during heavy rain last week.', 'alex@student.edu | +1234567897', 'Pending');

-- Create Index for Faster Queries
CREATE INDEX idx_category ON items(category);
CREATE INDEX idx_status ON items(status);
CREATE INDEX idx_type ON items(item_type);
CREATE INDEX idx_date ON items(date_reported);

-- Display the created table structure
DESCRIBE items;

-- Display inserted data
SELECT * FROM items;

echo "Database 'campus_lost_found' created successfully!";
