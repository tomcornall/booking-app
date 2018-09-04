CREATE TABLE booking (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username VARCHAR(80) NOT NULL,
  reason VARCHAR(255) NOT NULL,
  start_date DATETIME NOT NULL,
  end_date DATETIME NOT NULL
);
INSERT INTO booking (username, reason, start_date, end_date) VALUES ('Tom Johnson', 'Back hurts', '2018-10-20T08:00', '2018-10-20T10:00');
INSERT INTO booking (username, reason, start_date, end_date) VALUES ('Tom Johnson 2', 'Back hurts 2', '2018-10-20T08:00', '2018-10-20T10:00');
INSERT INTO booking (username, reason, start_date, end_date) VALUES ('Tom Johnson 3rd', 'Back hurts 2', '2018-10-20T08:00', '2018-10-20T10:00');