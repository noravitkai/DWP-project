DROP DATABASE IF EXISTS cinema_db;

CREATE DATABASE IF NOT EXISTS cinema_db;
USE cinema_db;

CREATE TABLE PostalCode (
    PostalCode VARCHAR(20),
    City VARCHAR(100),
    PRIMARY KEY (PostalCode)
);

CREATE TABLE User (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Email VARCHAR(255),
    `Password` VARCHAR(255),
    `Role` VARCHAR(50)
);

CREATE TABLE Movie (
    MovieID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255),
    Subtitle VARCHAR(255),
    Duration INT,
    Genre VARCHAR(100),
    ReleaseYear YEAR,
    Director VARCHAR(255),
    MovieDescription TEXT
);

CREATE TABLE Room (
    RoomID INT AUTO_INCREMENT PRIMARY KEY,
    RoomLabel VARCHAR(100),
    TotalSeats INT
);

CREATE TABLE Actor (
    ActorID INT AUTO_INCREMENT PRIMARY KEY,
    FirstName VARCHAR(50),
    LastName VARCHAR(50),
    `Role` VARCHAR(255)
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
    PostalCode VARCHAR(20),
    FOREIGN KEY (PostalCode) REFERENCES PostalCode(PostalCode) ON DELETE CASCADE
);

CREATE TABLE MovieImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255),
    MovieID INT,
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE
);

CREATE TABLE Features (
    MovieID INT,
    ActorID INT,
    PRIMARY KEY (MovieID, ActorID),
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE,
    FOREIGN KEY (ActorID) REFERENCES Actor(ActorID) ON DELETE CASCADE
);

CREATE TABLE Screening (
    ScreeningID INT AUTO_INCREMENT PRIMARY KEY,
    Price DECIMAL(10, 2),
    ScreeningDate DATE,
    ScreeningTime TIME,
    MovieID INT,
    RoomID INT,
    FOREIGN KEY (MovieID) REFERENCES Movie(MovieID) ON DELETE CASCADE,
    FOREIGN KEY (RoomID) REFERENCES Room(RoomID) ON DELETE CASCADE
);

CREATE TABLE Reservation (
    ReservationID INT AUTO_INCREMENT PRIMARY KEY,
    NumberOfSeats INT,
    ReservationDate DATE,
    ReservationStatus ENUM('Confirmed', 'Pending', 'Cancelled') DEFAULT 'Pending',
    ScreeningID INT,
    CustomerID INT,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE CASCADE
);

CREATE TABLE Ticket (
    TicketID INT AUTO_INCREMENT PRIMARY KEY,
    `Row` VARCHAR(10),
    SeatNumber INT,
    ReservationID INT,
    ScreeningID INT,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE CASCADE,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE
);

CREATE TABLE Payment (
    PaymentID INT AUTO_INCREMENT PRIMARY KEY,
    PaymentStatus VARCHAR(50),
    TransactionAmount DECIMAL(10, 2),
    TransactionDate DATE,
    CustomerID INT,
    ReservationID INT,
    FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID) ON DELETE CASCADE,
    FOREIGN KEY (ReservationID) REFERENCES Reservation(ReservationID) ON DELETE CASCADE
);

CREATE TABLE News (
    NewsID INT AUTO_INCREMENT PRIMARY KEY,
    Title VARCHAR(255),
    Content TEXT,
    DatePublished DATE,
    Category ENUM('Event', 'Announcement', 'Update') DEFAULT 'Announcement',
    UserID INT,
    FOREIGN KEY (UserID) REFERENCES User(UserID) ON DELETE CASCADE
);

CREATE TABLE Event (
    EventID INT AUTO_INCREMENT PRIMARY KEY,
    EventName VARCHAR(255),
    EventDate DATE,
    EventDescription TEXT,
    Discount DECIMAL(5,2),
    ScreeningID INT,
    FOREIGN KEY (ScreeningID) REFERENCES Screening(ScreeningID) ON DELETE CASCADE
);

CREATE TABLE NewsImage (
    ImageID INT AUTO_INCREMENT PRIMARY KEY,
    ImageURL VARCHAR(255),
    NewsID INT,
    FOREIGN KEY (NewsID) REFERENCES News(NewsID) ON DELETE CASCADE
);