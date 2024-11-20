DROP DATABASE IF EXISTS cinema_db;

CREATE DATABASE IF NOT EXISTS cinema_db;
USE cinema_db;

CREATE TABLE Cinema (
    CinemaID INT AUTO_INCREMENT PRIMARY KEY,
    Tagline VARCHAR(255) NOT NULL,
    `Description` TEXT NOT NULL,
    PhoneNumber VARCHAR(20),
    Email VARCHAR(255) NOT NULL
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
    ReleaseYear YEAR,
    Director VARCHAR(255),
    MovieDescription TEXT
);

CREATE TABLE Actor (
    ActorID INT AUTO_INCREMENT PRIMARY KEY,
    FullName VARCHAR(100) NOT NULL,
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
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    Email VARCHAR(255),
    `Password` VARCHAR(255),
    PhoneNumber VARCHAR(20),
    SuiteNumber VARCHAR(50),
    Street VARCHAR(255),
    Country VARCHAR(100),
    PostalCodeID INT,
    FOREIGN KEY (PostalCodeID) REFERENCES PostalCode(PostalCodeID) ON DELETE CASCADE
);

CREATE TABLE Screening (
    ScreeningID INT AUTO_INCREMENT PRIMARY KEY,
    Price DECIMAL(10, 2) NOT NULL CHECK (Price > 0),
    ScreeningDate DATE NOT NULL,
    ScreeningTime TIME NOT NULL,
    MovieID INT NOT NULL,
    RoomID INT NOT NULL,
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE,
    FOREIGN KEY (RoomID) REFERENCES Room(RoomID) ON DELETE CASCADE
);

CREATE TABLE Reservation (
    ReservationID INT AUTO_INCREMENT PRIMARY KEY,
    NumberOfSeats INT UNSIGNED NOT NULL CHECK (NumberOfSeats > 0),
    ReservationDate DATE NOT NULL,
    ReservationStatus ENUM('Confirmed', 'Pending', 'Cancelled') DEFAULT 'Pending',
    ScreeningID INT NOT NULL,
    CustomerID INT NOT NULL,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE CASCADE
);

CREATE TABLE Ticket (
    TicketID INT AUTO_INCREMENT PRIMARY KEY,
    `Row` VARCHAR(10) NOT NULL,
    SeatNumber INT UNSIGNED NOT NULL CHECK (SeatNumber > 0),
    ReservationID INT NOT NULL,
    ScreeningID INT NOT NULL,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE CASCADE,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE
);

CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    PaymentStatus VARCHAR(50) NOT NULL,
    TransactionAmount DECIMAL(10, 2) NOT NULL CHECK (TransactionAmount > 0),
    TransactionDate DATE NOT NULL,
    CustomerID INT NOT NULL,
    ReservationID INT NOT NULL,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE CASCADE,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE CASCADE
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
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE
);

CREATE TABLE NewsImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255) NOT NULL,
    NewsID INT NOT NULL,
    FOREIGN KEY (NewsID) REFERENCES News(NewsID) ON DELETE CASCADE
);