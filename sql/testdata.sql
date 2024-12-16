INSERT INTO PostalCode (PostalCode, City) 
VALUES 
    ('6700', 'Esbjerg');

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
    ('Fast & Furious 2', '2 Fast 2 Be Taken Seriously', 107, 'Action, Crime', 2003, 'John Singleton', 'Brian and Roman prove that cars can solve any problem, as long as the problem doesn’t involve basic logic. Our heros take down a drug lord by doing what they do best – driving fast. Makes perfect sense.'),
    ('Fast & Furious 3', 'Cultural Appropriation Drift', 104, 'Action, Crime', 2006, 'Justin Lin', 'In a desperate attempt to stay relevant, this installment sends an Alabama high schooler to Japan with a complete lack of context. Proving once again that American teens can conquer anything, even complex Japanese driving techniques.'),
    ('Fast & Furious 4', 'Because Tokyo Didn’t Work', 107, 'Action, Crime', 2009, 'Justin Lin', 'After realizing nobody understood Tokyo Drift, Dom returns to basics: fast cars and his undying love of family. The plot somehow involves both street racing and drug cartels because why not?'),
    ('Fast & Furious 5', 'Rio’s Worst Day of Traffic', 130, 'Action, Crime', 2011, 'Justin Lin', 'Dom and his crew face their greatest challenge yet: keeping the plot grounded while physics takes a backseat. They discover that the best way to transport millions is by dragging a vault with two cars through the streets.'),
    ('Fast & Furious 6', 'Tanks for the Memories', 130, 'Action, Adventure', 2013, 'Justin Lin', 'Apparently regular street racing has grown too boring so they throw in a tank and pretend it makes sense. It’s not about the size of the tank, it’s about the size of Dom’s speeches about family.'),
    ('Fast & Furious 7', 'Gravity''s Last Stand', 137, 'Action, Adventure', 2015, 'James Wan', 'Gravity is a suggestion, not a rule, as the team parachutes cars from planes and jumps between skyscrapers. Physics left the chat. Dom still talks about family, though.'),
    ('Fast & Furious 8', 'Ice, Ice, Baby', 136, 'Action, Crime', 2017, 'F. Gary Gray', 'Dom betrays his crew, but it’s cool because it’s all for family. Anyway, it’s just an excuse to make cars drive on ice, fight a submarine, and prove that even when Dom is the villain, he’s still the hero.'),
    ('Fast & Furious 9', 'Magnetic Car Nonsense', 143, 'Action, Adventure', 2021, 'Justin Lin', 'Turns out magnets solve everything. They defy physics, logic, and all storytelling sense. If you thought they couldn’t top themselves, think again. Also, Dom’s brother shows up out of nowhere.'),
    ('Fast & Furious 10', 'World DOMination', 142, 'Action, Adventure', 2023, 'Louis Leterrier', 'The movie’s speed matches the pace at which the writers abandoned all sense of realism. Dom must face his toughest enemy yet: a plot that makes less sense than his moral code. But don’t worry, the answer is still fast cars and family, naturally.');

INSERT INTO Actor (FirstName, LastName, `Role`)
VALUES 
    ('Vin', 'Diesel', 'Dominic Toretto'),
    ('Paul', 'Walker', 'Brian O’Conner'),
    ('Michelle', 'Rodriguez', 'Letty Ortiz'),
    ('Jordana', 'Brewster', 'Mia Toretto'),
    ('Johnny', 'Strong', 'Leon'),
    ('Rick', 'Yune', 'Johnny Tran'),
    ('Matt', 'Schulze', 'Vince'),
    ('Chad', 'Lindberg', 'Jesse'),
    ('Tyrese', 'Gibson', 'Roman Pearce'),
    ('Cole', 'Hauser', 'Carter Verone'),
    ('Devon', 'Aoki', 'Suki'),
    ('Eva', 'Mendes', 'Monica Fuentes'),
    ('Ludacris', NULL, 'Tej Parker'),
    ('Amaury', 'Nolasco', 'Orange Julius'),
    ('Lucas', 'Black', 'Sean Boswell'),
    ('Sung', 'Kang', 'Han Lue'),
    ('Brian', 'Tee', 'D.K.'),
    ('Nathalie', 'Kelley', 'Neela'),
    ('Nikki', 'Griffin', 'Cindy'),
    ('Bow', 'Wow', 'Twinkie'),
    ('Zachery Ty', 'Bryan', 'Clay'),
    ('Gal', 'Gadot', 'Gisele Yashar'),
    ('Laz', 'Alonso', 'Fenix Rise'),
    ('John', 'Ortiz', 'Brage'),
    ('Dwayne', 'Johnson', 'Luke Hobbs'),
    ('Liza', 'Lapira', 'Trinh'),
    ('Greg', 'Cipes', 'Dwight'),
    ('Elsa', 'Pataky', 'Elena Neves'),
    ('Gina', 'Carano', 'Riley'),
    ('Luke', 'Evans', 'Owen Shaw'),
    ('Cody', 'Walker', 'Brian O’Conner'),
    ('Nathalie', 'Emmanuel', 'Megan'),
    ('Jason', 'Statham', 'Ian Shaw'),
    ('Charlize', 'Theron', 'Cipher'),
    ('Scott', 'Eastwood', 'Eric Reisner'),
    ('John', 'Cena', 'Jakob Toretto'),
    ('Helen', 'Mirren', 'Magdalene Shaw'),
    ('Cardi B', NULL, 'Leysa'),
    ('Jason', 'Momoa', 'Dante Reyes'),
    ('Anan', 'Ritchson', 'Aimes'),
    ('Rita', 'Moreno', 'Abuelita Toretto'),
    ('Brie', 'Larson', 'Tess');

INSERT INTO Features (MovieID, ActorID)
VALUES
    (1, 1),
    (1, 2),
    (1, 3),
    (1, 4),
    (1, 5),
    (1, 6),
    (1, 7),
    (1, 8),
    (2, 1),
    (2, 2),
    (2, 9),
    (2, 10),
    (2, 11),
    (2, 12),
    (2, 13),
    (2, 14),
    (3, 15),
    (3, 16),
    (3, 17),
    (3, 18),
    (3, 19),
    (3, 20),
    (3, 21),
    (4, 1),
    (4, 2),
    (4, 22),
    (4, 23),
    (4, 24),
    (4, 25),
    (4, 26),
    (4, 27),
    (5, 1),
    (5, 2),
    (5, 28),
    (5, 4),
    (5, 22),
    (5, 11),
    (5, 25),
    (5, 16),
    (6, 1),
    (6, 2),
    (6, 3),
    (6, 25),
    (6, 4),
    (6, 29),
    (6, 16),
    (6, 30),
    (7, 1),
    (7, 2),
    (7, 31),
    (7, 32),
    (7, 33),
    (7, 3),
    (7, 28),
    (8, 1),
    (8, 25),
    (8, 3),
    (8, 33),
    (8, 34),
    (8, 9),
    (8, 32),
    (8, 35),
    (8, 13),
    (9, 1),
    (9, 36),
    (9, 3),
    (9, 16),
    (9, 37),
    (9, 34),
    (9, 38),
    (10, 1),
    (10, 39),
    (10, 40),
    (10, 3),
    (10, 41),
    (10, 42),
    (10, 16),
    (10, 33),
    (10, 9);

INSERT INTO Customer (FirstName, LastName, Email, `Password`, PhoneNumber, SuiteNumber, Street, Country, PostalCodeID) 
VALUES 
    ('Simon', 'Jobbágy', 'jobbagy.simon@gmail.com', '$2y$10$N2VQRDOKxX3w.5pI/vgQUukSb8pGVRG.nKa8Gd4ojA2WiuhHbF2X2', NULL, NULL, NULL, 'Denmark', 1);

INSERT INTO Screening (Price, ScreeningDate, ScreeningTime, MovieID, RoomID)
VALUES
    (120.00, '2024-12-16', '17:00:00', 1, 1),
    (135.00, '2024-12-16', '19:30:00', 2, 1),
    (135.00, '2024-12-18', '18:30:00', 3, 2),
    (140.00, '2024-12-28', '14:00:00', 4, 1),
    (140.00, '2024-12-28', '20:30:00', 5, 1),
    (140.00, '2024-12-29', '17:15:00', 6, 2),
    (120.00, '2025-01-05', '15:00:00', 7, 1),
    (120.00, '2025-01-05', '15:00:00', 8, 2),
    (120.00, '2025-01-07', '16:00:00', 9, 1),
    (135.00, '2025-01-07', '19:00:00', 10, 1),
    (120.00, '2025-01-09', '17:15:00', 1, 2),
    (135.00, '2025-01-09', '20:00:00', 2, 2),
    (120.00, '2025-01-11', '14:00:00', 3, 1),
    (120.00, '2025-01-11', '16:45:00', 4, 1),
    (120.00, '2025-01-11', '16:30:00', 5, 2),
    (120.00, '2025-01-13', '16:15:00', 6, 1),
    (135.00, '2025-01-13', '19:30:00', 7, 2),
    (120.00, '2025-01-14', '17:00:00', 8, 1),
    (135.00, '2025-01-14', '20:00:00', 9, 1),
    (135.00, '2025-01-14', '20:00:00', 10, 2);

INSERT INTO Reservation (NumberOfSeats, GuestFirstName, GuestLastName, GuestEmail, GuestPhoneNumber, ScreeningID, CustomerID, `Status`, ReservationToken) 
VALUES
    (2, NULL, NULL, NULL, NULL, 5, 1, 'Confirmed', NULL),
    (3, 'Jane', 'Doe', 'jane.doe@example.com', '555-1234', 2, NULL, 'Canceled', NULL),
    (2, NULL, NULL, NULL, NULL, 13, 1, 'Confirmed', NULL);

INSERT INTO Allocations (ReservationID, SeatID)
VALUES
    (1, 1),
    (1, 11),
    (3, 3),
    (3, 13);

INSERT INTO Payment (PaymentStatus, TransactionAmount, TransactionDate, StripeSessionID, StripePaymentIntentID, CustomerID, ReservationID) 
VALUES
    ('Completed', 280.00, '2024-12-07 17:15:08', 'cs_sess_X7bKLM3nP9QrTuvW4xYz5aBcD6eF7gH8iJ9kL0mN1oP2qR3sT4uV5wX6yZ7', 'test_4R6sT7uV8wX9yZ0aB1cD2eF3gH4iJ5kL6mN7oP8qR9sT0uV1wX2yZ3aB4', 1, 1),
    ('Completed', 240.00, '2024-12-16 12:47:00', 'cs_sess_M9nOpQ1rS2tU3vW4xY5zA6bC7dE8fG9hI0jK1lM2nO3pQ4rS5tU6vW7xY8zA9', 'test_5S7tU8vW9xY0zA1bC2dE3fG4hI5jK6lM7nO8pQ9rS0tU1vW2xY3zA4bC5dE6', 1, 3);

INSERT INTO News (Title, Content, DatePublished, Category) 
VALUES
    ('Upcoming Event: Toretto Tuesday!', 'Each week, fans of the Fast & Furious saga get the chance to win exclusive Fast & Furious memorabilia – because we know nothing brings people together like high-speed chases, over-the-top stunts, and, of course, family.\n\nJust show up, and you’ll be entered into the draw for collectibles that any true fan would be proud of. Will you be the lucky one to take home a mini model of Dom’s Charger or perhaps an official “Family” T-shirt? Only one way to find out: see you Tuesday!', '2024-11-01', 'Event'),
    ('Join us on Family Friday!', 'Watch movies with a 30% discount every Friday! (But only if you bring the family.)\n\nAre you ready for the deal of a lifetime? Or at least of the week? Family Friday is here, where you can watch movies at a 30% discount every Friday! But there’s a catch – a big one. This exclusive discount only applies if you bring the family. And yes, that includes everyone: Grandma, Uncle Bob, the cousin you barely know, and even that one nephew who insists on narrating every scene.\n\nEvery single Friday, bring the whole family for a 30% discount on all movies, because just like for Dom Toretto, here it’s all about family – and Family Friday is here to stay!', '2024-12-02', 'Announcement'),
    ('Update: More Parking for Family Fridays!', 'Good news for our Family Friday fans! Due to overwhelming demand (and way too many cars packed with “family” members), we’ve expanded our parking lot to accommodate all your loyal rides. Now, there’s even more space for you to park those Dodge Chargers, Honda Civics, and minivans full of “family” as you roll in to claim that sweet 30% discount.\n\nRemember, it’s all about family – and by family, we mean anyone you can fit in the car. So buckle up, bring the whole crew, and let’s keep making Family Fridays as packed as a Fast & Furious reunion!', '2024-12-16', 'Update');

INSERT INTO Cinema (Tagline, `Description`, PhoneNumber, Email, OpeningHours)
VALUES
    ('Celebrating the Legacy of Family and Speed', 'Welcome to Fast Lane Cine, devoted to the cinematic masterpiece that is the Fast & Furious saga. From its groundbreaking exploration of family dynamics to its bold defiance of physics, this franchise has redefined modern storytelling. We are proud to bring you a curated experience that honors the artistry of these iconic films. Join us in celebrating the most ambitious and heartfelt saga ever created!', '123-456-7890', 'contact@fastlanecine.com', 'Monday-Friday: 4:00 PM – 10:00 PM\nSaturday-Sunday: 2:00 PM – 11:00 PM');