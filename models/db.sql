CREATE TABLE users(
    id_user SERIAL PRIMARY KEY,
    nrp_user VARCHAR(191) NOT NULL,
    name_user VARCHAR(255) NOT NULL,
    department_user VARCHAR(191),
    hp_user VARCHAR(191),
    password_user VARCHAR(255) NOT NULL
);