INSERT INTO PostalCode (PostalCode, City) 
VALUES 
    ('12345', 'Race City');

INSERT INTO Admin (Email, `Password`)
VALUES
    ('vitkai.nora@gmail.com', '$2y$10$kcilhapT7/oMgtsqz3xf4.K1IcvMkviTtuHE7p848QtI9/RNx8IrO');

INSERT INTO Room (RoomLabel, TotalSeats) 
VALUES
    ('Family Lounge', 100),
    ('Quarter-Tank', 25);

INSERT INTO Seat (RowLabel, SeatNumber, RoomID)
SELECT 
    r.RowLabel, s.SeatNumber, 1 
FROM 
    (SELECT 'A' AS RowLabel UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E'
     UNION SELECT 'F' UNION SELECT 'G' UNION SELECT 'H' UNION SELECT 'I' UNION SELECT 'J') r,
    (SELECT n AS SeatNumber FROM (SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 
                                  UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10) s) s;

INSERT INTO Seat (RowLabel, SeatNumber, RoomID)
SELECT 
    r.RowLabel, s.SeatNumber, 2 
FROM 
    (SELECT 'A' AS RowLabel UNION SELECT 'B' UNION SELECT 'C' UNION SELECT 'D' UNION SELECT 'E') r,
    (SELECT n AS SeatNumber FROM (SELECT 1 n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5) s) s;

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

INSERT INTO Customer (FirstName, LastName, Email, `Password`, PhoneNumber, SuiteNumber, Street, Country, PostalCodeID) 
VALUES
    ('John', 'Doe', 'johndoe@example.com', 'password123', '000-1234', '5A', 'Main Street', 'Dreamland', 1);

INSERT INTO Screening (Price, ScreeningDate, ScreeningTime, MovieID, RoomID)
VALUES
    (12.50, '2024-10-05', '18:00:00', 1, 1),
    (10.00, '2024-10-06', '20:00:00', 2, 2),
    (15.00, '2024-10-07', '19:30:00', 5, 1);

INSERT INTO News (Title, Content, DatePublished, Category) 
VALUES
    ('Fast X Breaks Records!', 'The latest Fast & Furious movie has shattered expectations...', '2024-10-01', 'Announcement'),
    ('Upcoming Event: Torretto Tuesday!', 'Join us on Tuesdays when every guest gets a chance to win Fast & Furious memorabilia!', '2024-10-02', 'Event');

INSERT INTO Event (EventName, EventDate, EventDescription, Discount, ScreeningID) 
VALUES
    ('Torretto Tuesday', '2024-10-08', 'Special screenings of all Fast & Furious movies! Win memorabilia!', 10.00, 1),
    ('Family Friday', '2024-10-11', 'Bring the family for a 30% discount on all Fast & Furious screenings!', 30.00, 1);

INSERT INTO Cinema (Tagline, `Description`, PhoneNumber, Email, OpeningHours)
VALUES
    ('Fast Cars, Furious Movies!', 'Welcome to the ultimate destination for Fast & Furious movie fans. Experience high-octane entertainment and immersive cinema.', '123-456-7890', 'contact@fastlanecine.com', 'Monday-Friday: 10:00 AM - 10:00 PM\nSaturday-Sunday: 9:00 AM - 11:00 PM');

INSERT INTO CinemaImage (ImageURL, CinemaID)
VALUES 
    ('/DWP-project/uploads/family.jpg', 1);