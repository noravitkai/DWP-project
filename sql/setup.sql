DROP DATABASE IF EXISTS cinema_db;

CREATE DATABASE IF NOT EXISTS cinema_db;
USE cinema_db;

CREATE TABLE Cinema (
    CinemaID INT AUTO_INCREMENT PRIMARY KEY,
    Tagline VARCHAR(255) NOT NULL,
    `Description` TEXT NOT NULL,
    PhoneNumber VARCHAR(20),
    Email VARCHAR(255) NOT NULL,
    OpeningHours TEXT NOT NULL
);

CREATE TABLE CinemaImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255) NOT NULL,
    CinemaID INT NOT NULL,
    FOREIGN KEY (CinemaID) REFERENCES Cinema(CinemaID) ON DELETE CASCADE
);

CREATE TABLE PostalCode (
    PostalCodeID INT AUTO_INCREMENT PRIMARY KEY,
    PostalCode VARCHAR(20) NOT NULL,
    City VARCHAR(100) NOT NULL,
    UNIQUE (PostalCode, City)
);

CREATE TABLE Admin (
    AdminID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255) NOT NULL UNIQUE,
    `Password` VARCHAR(255) NOT NULL
);

CREATE TABLE Room (
    RoomID INT AUTO_INCREMENT PRIMARY KEY,
    RoomLabel VARCHAR(100) NOT NULL,
    TotalSeats INT UNSIGNED NOT NULL CHECK (TotalSeats > 0)
);

CREATE TABLE Movie (
    MovieID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Subtitle VARCHAR(255),
    Duration INT UNSIGNED,
    Genre VARCHAR(100),
    ReleaseYear YEAR NOT NULL,
    Director VARCHAR(255),
    MovieDescription TEXT
);

CREATE TABLE Actor (
    ActorID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    `Role` VARCHAR(255)
);

CREATE TABLE Features (
    MovieID INT NOT NULL,
    ActorID INT NOT NULL,
    PRIMARY KEY (MovieID, ActorID),
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE,
    FOREIGN KEY (ActorID) REFERENCES Actor(ActorID) ON DELETE CASCADE
);

CREATE TABLE MovieImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255) NOT NULL,
    MovieID INT NOT NULL,
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE
);

CREATE TABLE Customer (
    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50) NOT NULL,
    LastName VARCHAR(50) NOT NULL,
    Email VARCHAR(255) NOT NULL UNIQUE,
    `Password` VARCHAR(255),
    PhoneNumber VARCHAR(20),
    SuiteNumber VARCHAR(50),
    Street VARCHAR(255),
    Country VARCHAR(100) NOT NULL,
    PostalCodeID INT NOT NULL,
    FOREIGN KEY (PostalCodeID) REFERENCES PostalCode(PostalCodeID) ON DELETE RESTRICT
);

CREATE TABLE Screening (
    ScreeningID INT AUTO_INCREMENT PRIMARY KEY,
    Price DECIMAL(10, 2) NOT NULL CHECK (Price > 0),
    ScreeningDate DATE NOT NULL,
    ScreeningTime TIME NOT NULL,
    MovieID INT NOT NULL,
    RoomID INT NOT NULL,
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE RESTRICT,
    FOREIGN KEY (RoomID) REFERENCES Room(RoomID) ON DELETE RESTRICT
);

CREATE TABLE Reservation (
    ReservationID INT AUTO_INCREMENT PRIMARY KEY,
    NumberOfSeats INT UNSIGNED NOT NULL CHECK (NumberOfSeats > 0),
    CreatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    GuestFirstName VARCHAR(50),
    GuestLastName VARCHAR(50),
    GuestEmail VARCHAR(255),
    GuestPhoneNumber VARCHAR(20),
    ScreeningID INT NOT NULL,
    CustomerID INT,
    `Status` ENUM('Pending', 'Confirmed', 'Canceled') NOT NULL DEFAULT 'Pending',
    ReservationToken VARCHAR(64) UNIQUE NULL,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE RESTRICT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE SET NULL,
    CHECK (
        (CustomerID IS NOT NULL AND GuestFirstName IS NULL AND GuestLastName IS NULL AND GuestEmail IS NULL AND GuestPhoneNumber IS NULL) OR
        (CustomerID IS NULL AND GuestFirstName IS NOT NULL AND GuestLastName IS NOT NULL AND GuestEmail IS NOT NULL AND GuestPhoneNumber IS NOT NULL)
    )
);

CREATE TABLE Seat (
    SeatID INT AUTO_INCREMENT PRIMARY KEY,
    RowLabel CHAR(1) NOT NULL,
    SeatNumber INT UNSIGNED NOT NULL CHECK (SeatNumber > 0),
    SeatStatus ENUM('Available', 'Reserved') NOT NULL DEFAULT 'Available',
    RoomID INT NOT NULL,
    FOREIGN KEY (RoomID) REFERENCES Room(RoomID) ON DELETE CASCADE,
    UNIQUE (RowLabel, SeatNumber, RoomID)
);

CREATE TABLE Allocations (
    ReservationID INT NOT NULL,
    SeatID INT NOT NULL,
    PRIMARY KEY (ReservationID, SeatID),
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE CASCADE,
    FOREIGN KEY (SeatID) REFERENCES Seat(SeatID) ON DELETE CASCADE
);

CREATE TABLE Ticket (
    TicketID INT AUTO_INCREMENT PRIMARY KEY,
    SeatID INT NOT NULL,
    ReservationID INT NOT NULL,
    ScreeningID INT NOT NULL,
    FOREIGN KEY (SeatID) REFERENCES Seat(SeatID) ON DELETE RESTRICT,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE RESTRICT,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE RESTRICT
);

CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    PaymentStatus ENUM('Pending', 'Canceled', 'Completed') NOT NULL DEFAULT 'Pending',
    TransactionAmount DECIMAL(10, 2) NOT NULL CHECK (TransactionAmount > 0),
    TransactionDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    StripeSessionID VARCHAR(255) UNIQUE,
    StripePaymentIntentID VARCHAR(255) UNIQUE,
    CustomerID INT,
    ReservationID INT NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE SET NULL,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE RESTRICT
);

CREATE TABLE News (
    NewsID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255) NOT NULL,
    Content TEXT NOT NULL,
    DatePublished DATE NOT NULL,
    Category ENUM('Event', 'Announcement', 'Update') DEFAULT 'Announcement'
);

CREATE TABLE Event (
    EventID INT AUTO_INCREMENT PRIMARY KEY,
    EventName VARCHAR(255) NOT NULL,
    EventDate DATE NOT NULL,
    EventDescription TEXT,
    Discount DECIMAL(5,2) CHECK (Discount >= 0),
    ScreeningID INT NOT NULL,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE RESTRICT
);

CREATE TABLE NewsImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255) NOT NULL,
    NewsID INT NOT NULL,
    FOREIGN KEY (NewsID) REFERENCES News(NewsID) ON DELETE CASCADE
);

CREATE VIEW CinemaDetails AS
SELECT 
    c.*,
    ci.ImageURL
FROM 
    Cinema c
LEFT JOIN 
    CinemaImage ci ON c.CinemaID = ci.CinemaID;

CREATE VIEW CustomerDetails AS
SELECT
    c.CustomerID,
    c.FirstName,
    c.LastName,
    c.Email,
    c.Password,
    c.PhoneNumber,
    c.SuiteNumber,
    c.Street,
    c.Country,
    c.PostalCodeID,
    p.PostalCode,
    p.City
FROM
    Customer c
INNER JOIN
    PostalCode p ON c.PostalCodeID = p.PostalCodeID;

CREATE VIEW MovieDetails AS
SELECT
    m.MovieID,
    m.Title,
    m.Subtitle,
    m.Duration,
    m.Genre,
    m.ReleaseYear,
    m.Director,
    m.MovieDescription,
    mi.ImageURL
FROM 
    Movie m
LEFT JOIN 
    MovieImage mi ON m.MovieID = mi.MovieID;

    CREATE VIEW NewsDetails AS
SELECT 
    n.NewsID,
    n.Title,
    n.Content,
    n.DatePublished,
    n.Category,
    ni.ImageURL
FROM 
    News n
LEFT JOIN 
    NewsImage ni ON n.NewsID = ni.NewsID;

CREATE VIEW ReservationDetails AS
SELECT
    r.ReservationID,
    r.NumberOfSeats,
    r.CreatedAt,
    r.GuestFirstName,
    r.GuestLastName,
    r.GuestEmail,
    r.GuestPhoneNumber,
    r.ScreeningID,
    r.CustomerID,
    r.Status,
    s.ScreeningDate,
    s.ScreeningTime,
    s.Price,
    m.Title AS MovieTitle,
    c.FirstName AS CustomerFirstName,
    c.LastName AS CustomerLastName,
    c.Email AS CustomerEmail,
    c.PhoneNumber AS CustomerPhoneNumber,
    (s.Price * r.NumberOfSeats) AS TotalPrice
FROM 
    Reservation r
JOIN 
    Screening s ON r.ScreeningID = s.ScreeningID
JOIN 
    Movie m ON s.MovieID = m.MovieID
LEFT JOIN 
    Customer c ON r.CustomerID = c.CustomerID;

CREATE VIEW DailyScreenings AS
SELECT 
    s.ScreeningID,
    s.ScreeningTime,
    m.MovieID,
    m.Title AS MovieTitle,
    mi.ImageURL
FROM 
    Screening s
JOIN 
    Movie m ON s.MovieID = m.MovieID
LEFT JOIN 
    MovieImage mi ON m.MovieID = mi.MovieID
WHERE 
    s.ScreeningDate = CURDATE();

DELIMITER $$
CREATE TRIGGER trg_after_allocations_insert
AFTER INSERT ON Allocations
FOR EACH ROW
BEGIN
    UPDATE Seat 
    SET SeatStatus = 'Reserved' 
    WHERE SeatID = NEW.SeatID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_after_allocations_delete
AFTER DELETE ON Allocations
FOR EACH ROW
BEGIN
    UPDATE Seat 
    SET SeatStatus = 'Available' 
    WHERE SeatID = OLD.SeatID;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_after_customer_update
AFTER UPDATE ON Customer
FOR EACH ROW
BEGIN
    DECLARE postalCodeUsage INT;
    IF NEW.PostalCodeID <> OLD.PostalCodeID THEN
        SELECT COUNT(*) INTO postalCodeUsage 
        FROM Customer 
        WHERE PostalCodeID = OLD.PostalCodeID;
        IF postalCodeUsage = 0 THEN
            DELETE FROM PostalCode WHERE PostalCodeID = OLD.PostalCodeID;
        END IF;
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_before_screening_insert
BEFORE INSERT ON Screening
FOR EACH ROW
BEGIN
    DECLARE overlapCount INT;
    DECLARE buffer_time INT DEFAULT 15;

    SELECT COUNT(*) INTO overlapCount
    FROM Screening s
    JOIN Movie m ON s.MovieID = m.MovieID
    JOIN Movie new_m ON NEW.MovieID = new_m.MovieID
    WHERE 
        s.RoomID = NEW.RoomID
        AND s.ScreeningDate = NEW.ScreeningDate
        AND (
            NEW.ScreeningTime < ADDTIME(s.ScreeningTime, SEC_TO_TIME(m.Duration * 60 + buffer_time * 60))
            AND ADDTIME(NEW.ScreeningTime, SEC_TO_TIME(new_m.Duration * 60 + buffer_time * 60)) > s.ScreeningTime
        );
    
    IF overlapCount > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Screening time overlaps with an existing screening or does not allow enough buffer time.';
    END IF;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER trg_before_screening_update
BEFORE UPDATE ON Screening
FOR EACH ROW
BEGIN
    DECLARE overlapCount INT;
    DECLARE buffer_time INT DEFAULT 15;

    SELECT COUNT(*) INTO overlapCount
    FROM Screening s
    JOIN Movie m ON s.MovieID = m.MovieID
    JOIN Movie new_m ON NEW.MovieID = new_m.MovieID
    WHERE 
        s.RoomID = NEW.RoomID
        AND s.ScreeningDate = NEW.ScreeningDate
        AND s.ScreeningID <> NEW.ScreeningID
        AND (
            NEW.ScreeningTime < ADDTIME(s.ScreeningTime, SEC_TO_TIME(m.Duration * 60 + buffer_time * 60))
            AND ADDTIME(NEW.ScreeningTime, SEC_TO_TIME(new_m.Duration * 60 + buffer_time * 60)) > s.ScreeningTime
        );
    
    IF overlapCount > 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Screening time overlaps with an existing screening or does not allow enough buffer time.';
    END IF;
END$$
DELIMITER ;