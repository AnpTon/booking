-- Database: booking_system
CREATE Database Library;
USE Library;
-- Library Users

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('librarian', 'user') NOT NULL DEFAULT 'user',
    date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Insert Into users (name, email, password, role)
Values('John Doe', 'jdoe@gmail.com', '$2y$10$DEYY5R2ggjSeE.ariSiLZ.cmITttVwHT6uFPQivZPT8FG02eEwQmG', 'librarian'),
('Jane Dae', 'jdae@gmail.com', '$2y$10$EvxlRXwatAWl6oIBf/abD.IfIJJ930Ip2kyR5.Q.Vky7yNF9bXCU6', 'user');
-- Bookss
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('available', 'borrowed') NOT NULL DEFAULT 'available',
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Insert Into books (title, author, description)
VALUES('Whatever, Whenever', 'William Williom', 'Whenever is Whatever but can Whatever truly be Whenever'),
('Wherever, Whenever', 'Williom William', 'Whenever is Wherever but can Wherever truly be Whenever');
-- Borrow Records
CREATE TABLE borrowrecords (
    record_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    status ENUM('pending', 'approved', 'return_pending', 'returned', 'rejected') 
           NOT NULL DEFAULT 'pending',
    borrow_date DATETIME DEFAULT NULL,
    return_date DATETIME DEFAULT NULL,
    date_requested TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);
