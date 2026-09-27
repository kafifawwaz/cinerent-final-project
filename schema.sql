CREATE TABLE Users (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    gender VARCHAR(10) NULL,
    dateOfBirth DATE NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'member',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE Genre (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE Films (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT NULL,
    director VARCHAR(50) NOT NULL,
    releaseYear INT(10) NOT NULL,
    stock INT(10) NOT NULL DEFAULT 0,
    genreId INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_films_genre
        FOREIGN KEY (genreId) REFERENCES Genre(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

CREATE TABLE Cart (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    userId INT(10) UNSIGNED NOT NULL,
    filmid INT(10) UNSIGNED NOT NULL,
    addedAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_cart_user_film (userId, filmid), 
    CONSTRAINT fk_cart_user
        FOREIGN KEY (userId) REFERENCES Users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_cart_film
        FOREIGN KEY (filmid) REFERENCES Films(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE Rentals (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    userId INT(10) UNSIGNED NOT NULL,
    borrowDate DATE NOT NULL,
    dueDate DATE NOT NULL,
    returnDate DATE NULL,
    status VARCHAR(15) NOT NULL DEFAULT 'borrowed',
    createdAt DATETIME DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_rentals_user
        FOREIGN KEY (userId) REFERENCES Users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

CREATE TABLE RentalDetails (
    id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    rentalId INT(10) UNSIGNED NOT NULL,
    filmid INT(10) UNSIGNED NOT NULL,
    PRIMARY KEY (id),
    CONSTRAINT fk_rd_rental
        FOREIGN KEY (rentalId) REFERENCES Rentals(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_rd_film
        FOREIGN KEY (filmid) REFERENCES Films(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);

-- ==========================================
-- DATA DUMMY / SEED DATA UNTUK TESTING
-- Notes: Data Dummy ini di-insert agar halaman katalog, 
-- home, dan admin tidak kosong saat aplikasi pertama kali di-run.
-- ==========================================

-- 1. Account Admin Default [pw : Admin@1234]
INSERT INTO Users (username, email, password, gender, dateOfBirth, role) VALUES
('admin', 'admin@cinerent.com', '$2a$12$WbQEZ4ok5VMe6/evcnKowOr6mp2DotJHkz3v90EbDFWFZnO/Mqzxq', 'Male', '2005-05-05', 'admin');

-- 2. Genre Film (berdasarkan reference film case)
INSERT INTO Genre (name) VALUES
('Action'),
('Drama'),
('Horror'),
('Comedy'),
('Science Fiction'),
('Romance'),
('Thriller'),
('Musical'),
('Crime'),
('Animation');

-- 3. Data Film 
INSERT INTO Films (title, description, director, releaseYear, stock, genreId) VALUES
('Forrest Gump', 'The presidencies of Kennedy and Johnson, the Vietnam War, and other historical events unfold from the perspective of an Alabama man with an IQ of 75.', 'Robert Zemeckis', 1994, 5, 2),
('Get Out', 'A young African-American visits his white girlfriend\'s parents for the weekend, where his uneasiness about their reception eventually reaches a boiling point.', 'Jordan Peele', 2017, 3, 3),
('Hereditary', 'A grieving family is haunted by tragic and disturbing occurrences after the death of their secretive grandmother.', 'Ari Aster', 2018, 4, 3),
('Home Alone', 'An eight-year-old troublemaker must protect his house from a pair of burglars when he is accidentally left home alone by his family during Christmas vacation.', 'Chris Columbus', 1990, 6, 4),
('Inception', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.', 'Christopher Nolan', 2010, 5, 5),
('Interstellar', 'When Earth becomes uninhabitable in the future, a farmer and ex-NASA pilot is tasked to pilot a spacecraft, along with a team of researchers, to find a new planet for humans.', 'Christopher Nolan', 2014, 4, 5),
('La La Land', 'While navigating their careers in Los Angeles, a pianist and an actress fall in love while attempting to reconcile their aspirations for the future.', 'Damien Chazelle', 2016, 5, 8),
('Mad Max: Fury Road', 'In a post-apocalyptic wasteland, a woman rebels against a tyrannical ruler in search for her homeland with the aid of a group of female prisoners and a drifter named Max.', 'George Miller', 2015, 4, 1),
('Parasite', 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.', 'Bong Joon-ho', 2019, 5, 7),
('Pulp Fiction', 'The lives of two mob hitmen, a boxer, a gangster and his wife, and a pair of diner bandits intertwine in four tales of violence and redemption.', 'Quentin Tarantino', 1994, 3, 9),
('Spirited Away', 'During her family\'s move to the suburbs, a sullen 10-year-old girl wanders into a world ruled by gods, witches and spirits, a world where humans are changed into beasts.', 'Hayao Miyazaki', 2001, 5, 10),
('The Avengers', 'Earth\'s mightiest heroes must come together and learn to fight as a team if they are going to stop the mischievous Loki and his alien army from enslaving humanity.', 'Joss Whedon', 2012, 6, 1),
('The Dark Knight', 'When the menace known as the Joker wreaks havoc and chaos on the people of Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 'Christopher Nolan', 2008, 5, 1),
('The Grand Budapest Hotel', 'A writer encounters the owner of a aging high-class hotel, who tells him of his early years serving as a lobby boy in the hotel\'s glorious years under an exceptional concierge.', 'Wes Anderson', 2014, 4, 4),
('The Notebook', 'An epic love story centered around an older man who reads aloud to a woman with Alzheimer\'s disease from a faded notebook that contains the story of their youth.', 'Nick Cassavetes', 2004, 5, 6),
('The Shawshank Redemption', 'A banker convicted of insider murder forms a friendship over a number of years with a incarcerated contraband smuggling inmate while finding redemption through acts of common decency.', 'Frank Darabont', 1994, 5, 2),
('The Silence of the Lambs', 'A young F.B.I. cadet must receive the help of an incarcerated and manipulative cannibalistic killer to help catch another serial killer, a madman who skins his victims.', 'Jonathan Demme', 1991, 3, 7),
('Toy Story', 'A cowboy doll is profoundly threatened and jealous when a new spaceman action figure supplants him as top toy in a boy\'s bedroom.', 'John Lasseter', 1995, 6, 10);