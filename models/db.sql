CREATE TABLE users(
    id_user SERIAL PRIMARY KEY,
    nrp_user VARCHAR(18) NOT NULL,
    name_user VARCHAR(45) NOT NULL,
    department_user VARCHAR(45),
    hp_user VARCHAR(15),
    password_user VARCHAR(255) NOT NULL
);