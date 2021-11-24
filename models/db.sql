CREATE TABLE users(
    id_user SERIAL PRIMARY KEY,
    nrp_user VARCHAR(18) NOT NULL,
    name_user VARCHAR(45) NOT NULL,
    department_user VARCHAR(45),
    hp_user VARCHAR(15),
    password_user VARCHAR(255) NOT NULL
);

CREATE TABLE admins(
    id_admin SERIAL PRIMARY KEY,
    nip_admin VARCHAR(18) NOT NULL,
    name_admin VARCHAR(45) NOT NULL,
    password_admin VARCHAR(255) NOT NULL
);

CREATE TABLE tefl(
    id_tefl SERIAL PRIMARY KEY,
    id_user INTEGER NOT NULL,
    listening_tefl INTEGER,
    grammar_tefl INTEGER,
    reading_tefl INTEGER,
    avg_tefl INTEGER
);

ALTER TABLE tefl 
    ADD FOREIGN KEY (id_user) REFERENCES users(id_user);

ALTER TABLE users 
    ADD id_session_user VARCHAR(255);