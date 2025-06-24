CREATE TABLE IF NOT EXISTS booking_users_details (
    id INTEGER,
    salutation TEXT NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    email TEXT NOT NULL,
    phone TEXT NOT NULL,
    street TEXT NOT NULL,
    plz INTEGER NOT NULL,
    city TEXT NOT NULL,
    land TEXT NOT NULL,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS rooms (
    id INTEGER,
    name TEXT,
    PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS booking_details(
    id INTEGER,
    adult INTEGER NOT NULL,
    children INTEGER DEFAULT 0,
    room INTEGER NOT NULL,
    arrival NUMERIC,
    departure NUMERIC,
    is_paid INTEGER,    
    PRIMARY KEY (id),
    FOREIGN KEY (room) REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS bookings (
    id INTEGER,
    booking_id INTEGER,
    user INTEGER,
    arrival NUMERIC,
    departure NUMERIC,
    PRIMARY KEY (id), 
    FOREIGN KEY (booking_id) REFERENCES booking_details(id) ON DELETE CASCADE,
    FOREIGN KEY (arrival) REFERENCES booking_details(arrival) ON DELETE CASCADE,
    FOREIGN KEY (departure) REFERENCES booking_details(departure) ON DELETE CASCADE,
    FOREIGN KEY (user) REFERENCES booking_users_details(id) ON DELETE CASCADE

);

