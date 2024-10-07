INSERT INTO PostalCode (PostalCode, City) 
VALUES 
    ('12345', 'Race City'),
    ('56789', 'Speedville');

INSERT INTO Customer (FirstName, LastName, Email, `Password`, PhoneNumber, SuiteNumber, Street, Country, PostalCode) 
VALUES
    ('John', 'Doe', 'johndoe@example.com', 'password123', '000-1234', '5A', 'Main Street', 'Dreamland', '12345'), 
    ('Jane', 'Smith', 'janesmith@example.com', 'password456', '555-5678', '10B', 'Nowhere Street', 'Wonderland', '56789');

INSERT INTO User (Email, `Password`, `Role`) 
VALUES
    ('admin@example.com', 'adminpass', 'admin'),
    ('editor@example.com', 'editorpass', 'editor');

INSERT INTO Movie (Title, Subtitle, Duration, Genre, ReleaseYear, Director, MovieDescription) 
VALUES 
    ('Fast & Furious 1', 'Cars, Crime, and Culinary Curiosity', 106, 'Action, Crime', 2001, 'Rob Cohen', 'Brian’s detective work mostly involves eating his way to the top, while Dom shows everyone that racing isn’t as important as being a family man. Brian’s undercover strategy? Pretend to be a guy who loves tuna and illegal street racing. Brilliant plan.'),
    ('Fast & Furious 2', '2 Fast 2 Be Taken Seriously', 107, 'Action, Crime', 2003, 'John Singleton', 'Brian and Roman prove that cars can solve any problem, as long as the problem doesn’t involve basic logic.'),
    ('Fast & Furious 3', 'Cultural Appropriation Drift', 104, 'Action, Crime', 2006, 'Justin Lin', 'In a desperate attempt to stay relevant, this installment sends an Alabama high schooler to Japan with a complete lack of context.'),
    ('Fast & Furious 4', 'Because Tokyo Didn’t Work', 107, 'Action, Crime', 2009, 'Justin Lin', 'Dom returns to basics: fast cars and his undying love of family. The plot somehow involves both street racing and drug cartels.'),
    ('Fast & Furious 5', 'Rio’s Worst Day of Traffic', 130, 'Action, Crime', 2011, 'Justin Lin', 'Dom and his crew discover that the best way to transport millions is by dragging a vault with two cars through the streets.'),
    ('Fast & Furious 6', 'Tanks for the Memories', 130, 'Action, Adventure', 2013, 'Justin Lin', 'Apparently regular street racing has grown too boring so they throw in a tank.'),
    ('Fast & Furious 7', 'Gravity''s Last Stand', 137, 'Action, Adventure', 2015, 'James Wan', 'Gravity is a suggestion, not a rule, as the team parachutes cars from planes and jumps between skyscrapers.'),
    ('Fast & Furious 8', 'Ice, Ice, Baby', 136, 'Action, Crime', 2017, 'F. Gary Gray', 'Dom betrays his crew, but it’s cool because it’s all for family. Also, cars drive on ice and fight a submarine.'),
    ('Fast & Furious 9', 'Magnetic Car Nonsense', 143, 'Action, Adventure', 2021, 'Justin Lin', 'Magnets defy physics, logic, and all storytelling sense. Also, Dom’s brother shows up out of nowhere.'),
    ('Fast & Furious 10', 'World DOMination', 142, 'Action, Adventure', 2023, 'Louis Leterrier', 'Dom must face his toughest enemy yet: a plot that makes less sense than his moral code. But the answer is still fast cars and family.');

INSERT INTO Actor (FirstName, LastName, `Role`)
VALUES 
    ('Vin', 'Diesel', 'Dominic Toretto'),
    ('Paul', 'Walker', 'Brian O''Conner'),
    ('Michelle', 'Rodriguez', 'Letty Ortiz'),
    ('Jordana', 'Brewster', 'Mia Toretto'),
    ('Tyrese', 'Gibson', 'Roman Pearce'),
    ('Eva', 'Mendes', 'Monica Fuentes'),
    ('Ludacris', '', 'Tej Parker'),
    ('Lucas', 'Black', 'Sean Boswell'),
    ('Nathalie', 'Kelley', 'Neela'),
    ('Sung', 'Kang', 'Han Lue'),
    ('Bow Wow', '', 'Twinkie'),
    ('Dwayne', 'Johnson', 'Luke Hobbs'),
    ('Jason', 'Statham', 'Deckard Shaw'),
    ('Charlize', 'Theron', 'Cipher'),
    ('John', 'Cena', 'Jakob Toretto'),
    ('Jason', 'Momoa', 'Dante Reyes'),
    ('Brie', 'Larson', 'Tess');

INSERT INTO Features (MovieID, ActorID) 
VALUES 
    (1, 1), (1, 2), (1, 3), (1, 4),
    (2, 2), (2, 5), (2, 6), (2, 7),
    (3, 8), (3, 9), (3, 10), (3, 11),
    (4, 1), (4, 2), (4, 3), (4, 4),
    (5, 1), (5, 2), (5, 12), (5, 4),
    (6, 1), (6, 2), (6, 12), (6, 3),
    (7, 1), (7, 2), (7, 13), (7, 3),
    (8, 1), (8, 12), (8, 14), (8, 3),
    (9, 1), (9, 3), (9, 15), (9, 5), (9, 14),
    (10, 1), (10, 16), (10, 3), (10, 13), (10, 17);

INSERT INTO Room (RoomLabel, TotalSeats) 
VALUES
    ('Family Lounge', 100),
    ('Quarter-Tank', 25);

INSERT INTO Screening (Price, ScreeningDate, ScreeningTime, MovieID, RoomID)
VALUES
    (12.50, '2024-10-05', '18:00:00', 1, 1),
    (10.00, '2024-10-06', '20:00:00', 2, 2),
    (15.00, '2024-10-07', '19:30:00', 5, 1);

INSERT INTO Reservation (NumberOfSeats, ReservationDate, ReservationStatus, ScreeningID, CustomerID)
VALUES
    (2, '2024-10-03', 'Confirmed', 1, 1),
    (3, '2024-10-04', 'Pending', 2, 2);

INSERT INTO Ticket (`Row`, SeatNumber, ReservationID, ScreeningID) 
VALUES
    ('A', 1, 1, 1),
    ('A', 2, 1, 1),
    ('B', 1, 2, 2),
    ('B', 2, 2, 2);

INSERT INTO Payment (PaymentStatus, TransactionAmount, TransactionDate, CustomerID, ReservationID)
VALUES
    ('Completed', 25.00, '2024-10-03', 1, 1),
    ('Pending', 30.00, '2024-10-03', 2, 2);

INSERT INTO News (Title, Content, DatePublished, UserID) 
VALUES
    ('Fast X Breaks Records!', 'The latest Fast & Furious movie has shattered expectations...', '2024-10-01', 1),
    ('Upcoming Event: Torretto Tuesday!', 'Join us on Tuesdays when every guest gets a chance to win Fast & Furious memorabilia!', '2024-10-02', 2);

INSERT INTO Event (EventName, EventDate, EventDescription, Discount, ScreeningID) 
VALUES
    ('Torretto Tuesday', '2024-10-08', 'Special screenings of all Fast & Furious movies! Win memorabilia!', 10.00, 1),
    ('Family Friday', '2024-10-11', 'Bring the family for a 30% discount on all Fast & Furious screenings!', 30.00, 1);

INSERT INTO User (Email, `Password`, `Role`)
VALUES
    ('vitkai.nora@gmail.com', '$2y$10$kcilhapT7/oMgtsqz3xf4.K1IcvMkviTtuHE7p848QtI9/RNx8IrO', 'admin');