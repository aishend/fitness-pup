-- Ativar chaves estrangeiras no SQLite
PRAGMA foreign_keys = ON;


CREATE TABLE MembershipPlans (
    planID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    tagline TEXT,
    monthly_price REAL NOT NULL,
    features TEXT,
    is_featured BOOLEAN DEFAULT 0 CHECK(is_featured IN (0, 1))
);

-- tabelas para os diferentes tipos de users 
CREATE TABLE Users (
    userID INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    name TEXT,
    email TEXT UNIQUE,
    profilePhoto TEXT DEFAULT 'user-avatar.png',
    planID INTEGER,
    role TEXT CHECK(role IN ('admin', 'trainer', 'pet-trainer', 'member')) NOT NULL,
    bio TEXT,
    skip_pet_prompt BOOLEAN DEFAULT 0 CHECK(skip_pet_prompt IN (0, 1)),
    FOREIGN KEY (planID) REFERENCES MembershipPlans(planID) ON DELETE SET NULL
);

CREATE TABLE MemberStats (
    userID INTEGER PRIMARY KEY,
    workout_count INTEGER DEFAULT 0,
    weekly_streak INTEGER DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE
);


CREATE TABLE Badges (
    badgeID INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT NOT NULL,
    image_path TEXT NOT NULL
);

CREATE TABLE UserBadges (
    userID INTEGER,
    badgeID INTEGER,
    earned_date TEXT DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (userID, badgeID),
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,
    FOREIGN KEY (badgeID) REFERENCES Badges(badgeID) ON DELETE CASCADE
);

CREATE TABLE Trainers (
    trainerID INTEGER PRIMARY KEY AUTOINCREMENT,
    userID INTEGER,
    specialty TEXT,
    certifications TEXT,
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE
);

CREATE TABLE Administrators (
    adminID INTEGER PRIMARY KEY AUTOINCREMENT,
    userID INTEGER,
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE
);

CREATE TABLE Pets (
    petID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    breed TEXT,
    age INTEGER,
    ownerID INTEGER,
    vaccinated BOOLEAN DEFAULT 0 CHECK(vaccinated IN (0, 1)),
    photo TEXT DEFAULT 'default_pet.png',
    FOREIGN KEY (ownerID) REFERENCES Users(userID) ON DELETE CASCADE
);
-- Fim das tabelas dos tipos de users 


-- Comeco dos Appointments de Pessoas 
CREATE TABLE Classes (
    classID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    type TEXT,
    description TEXT,
    class_status TEXT CHECK(class_status IN ('upcoming', 'completed', 'cancelled')) DEFAULT 'upcoming',
    capacity INTEGER NOT NULL,
    start_time TEXT,
    end_time TEXT,
    trainerID INTEGER,
    class_image TEXT DEFAULT NULL,
    FOREIGN KEY (trainerID) REFERENCES Trainers(trainerID) ON DELETE SET NULL
);


CREATE TABLE Classes_Enrollments (
    enrollmentID INTEGER PRIMARY KEY AUTOINCREMENT,
    userID INTEGER,
    classID INTEGER,
    status TEXT CHECK(status IN ('enrolled', 'waitlisted', 'cancelled')) DEFAULT 'enrolled',
    enrollment_date TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userID) REFERENCES Users(userID) ON DELETE CASCADE,
    FOREIGN KEY (classID) REFERENCES Classes(classID) ON DELETE CASCADE,
    UNIQUE(userID, classID)
);
-- Fim dos Appointments de Pessoas 


-- Comeco das Reservas de espacos para os Pets

CREATE TABLE Pet_Rooms (
    pet_roomID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    capacity INTEGER NOT NULL,
    description TEXT,
    pet_trainer_userID INTEGER,
    FOREIGN KEY (pet_trainer_userID) REFERENCES Users(userID) ON DELETE SET NULL
);

CREATE TABLE Pet_Sessions (
    sessionID INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL,
    pet_roomID INTEGER NOT NULL,
    trainerID INTEGER NOT NULL,
    capacity INTEGER NOT NULL,
    FOREIGN KEY (pet_roomID) REFERENCES Pet_Rooms(pet_roomID) ON DELETE CASCADE,
    FOREIGN KEY (trainerID) REFERENCES Users(userID) ON DELETE SET NULL
);

CREATE TABLE Pet_Enrollments (
    enrollmentID INTEGER PRIMARY KEY AUTOINCREMENT,
    sessionID INTEGER NOT NULL,
    petID INTEGER NOT NULL,
    status TEXT CHECK(status IN ('enrolled', 'cancelled')) DEFAULT 'enrolled',
    enrollment_date TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sessionID) REFERENCES Pet_Sessions(sessionID) ON DELETE CASCADE,
    FOREIGN KEY (petID) REFERENCES Pets(petID) ON DELETE CASCADE,
    UNIQUE(sessionID, petID)
);

CREATE TABLE Reservations (
    reservationID INTEGER PRIMARY KEY AUTOINCREMENT,
    petID INTEGER NOT NULL,
    pet_roomID INTEGER NOT NULL,
    start_time TEXT NOT NULL,
    end_time TEXT NOT NULL,
    status TEXT CHECK(status IN ('confirmed', 'checked_in', 'checked_out', 'cancelled')) DEFAULT 'confirmed',
    FOREIGN KEY (petID) REFERENCES Pets(petID) ON DELETE CASCADE,
    FOREIGN KEY (pet_roomID) REFERENCES Pet_Rooms(pet_roomID) ON DELETE CASCADE
);
-- Fim dos Appointments de Pets



-- Administrados pelo admin e não estao associados a nada

CREATE TABLE EquipmentCategory (
    categoryID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL, 
    targetGroup TEXT CHECK(targetGroup IN ('Human', 'Pet')) NOT NULL
);


CREATE TABLE Equipment (
    equipmentID INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL, 
    description TEXT,
    categoryID INTEGER NOT NULL,
    status TEXT CHECK(status IN ('available', 'in_use', 'maintenance')) DEFAULT 'available',
    image TEXT DEFAULT NULL,
    FOREIGN KEY (categoryID) REFERENCES EquipmentCategory(categoryID) ON DELETE RESTRICT
);

-- Fim dos Administrados pelo admin e não estao associados a nada

-- Reviews

CREATE TABLE Reviews (
    reviewID INTEGER PRIMARY KEY AUTOINCREMENT,
    enrollmentID INTEGER UNIQUE,
    rating INTEGER CHECK(rating BETWEEN 1 AND 5) NOT NULL,
    comment TEXT,
    review_date TEXT DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (enrollmentID) REFERENCES Classes_Enrollments(enrollmentID) ON DELETE CASCADE
);

CREATE TABLE NewsletterSubscribers (
    subscriberID INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    subscribed_at TEXT DEFAULT CURRENT_TIMESTAMP
);

