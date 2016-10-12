CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(25) NOT NULL,
  `lastName` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `joinDate` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_uindex` (`email`)
);

CREATE TABLE `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `password` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_email_uindex` (`email`),
  CONSTRAINT `login_users_email_fk` FOREIGN KEY (`email`) REFERENCES `users` (`email`)
);

CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `callNum` varchar(30) NOT NULL,
  `barcode` double NOT NULL,
  `ISBN` varchar(13) NOT NULL,
  `topics` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `books_barcode_uindex` (`barcode`),
  UNIQUE KEY `books_ISBN_uindex` (`ISBN`)
);

CREATE TABLE `borrowings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL,
  `bookID` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `borrowings_users_id_fk` (`userID`),
  KEY `borrowings_books_id_fk` (`bookID`),
  CONSTRAINT `borrowings_books_id_fk` FOREIGN KEY (`bookID`) REFERENCES `books` (`id`),
  CONSTRAINT `borrowings_users_id_fk` FOREIGN KEY (`userID`) REFERENCES `users` (`id`)
);

-- Insert some sample values

INSERT INTO books (title, author, callNum, barcode, ISBN, topics) VALUES ("Fundamentals of web development", "Connolly, Randy", "006.7/151", "3000902563254", "9781292057095", "engineering computer science web development");
INSERT INTO books (title, author, callNum, barcode, ISBN, topics) VALUES ("Sports medicine", "Williams, J. G. P.", "617.1027/8", "300090523694", "0713142758", "medicine sport");
INSERT INTO books (title, author, callNum, barcode, ISBN, topics) VALUES ("Programming C#", "Liberty, Jesse", "005.133/C-2/2 ", "3000908521456", "0596004893", "computer science development c++");

INSERT INTO users (firstName, lastName, email, joinDate) VALUES ("Ben", "Jervis", "bij706@uowmail.edu.au", CURRENT_DATE());
INSERT INTO login (email, password) VALUES ("bij706@uowmail.edu.au", "userpassword");
INSERT INTO users (firstName, lastName, email, joinDate) VALUES ("Test", "User", "test@domain.com.au", CURRENT_DATE());
INSERT INTO login (email, password) VALUES ("test@domain.com.au", "testpassword");