CREATE TABLE korisnici (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ime VARCHAR(50) NOT NULL,
    prezime VARCHAR(50) NOT NULL,
    e_mail VARCHAR(100) NOT NULL UNIQUE,
    lozinka VARCHAR(255) NOT NULL,
    uloga ENUM('kupac','trgovac','admin') DEFAULT 'kupac'
);
 CREATE TABLE hrana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    naziv VARCHAR(100) NOT NULL,
    kalorije INT NOT NULL,
    proteini FLOAT,
    ugljikohidrati FLOAT,
    masti FLOAT
);
CREATE TABLE korisnicka_hrana (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    naziv VARCHAR(100) NOT NULL,
    kalorije INT NOT NULL,
    proteini FLOAT NOT NULL,
    ugljikohidrati FLOAT NOT NULL,
    masti FLOAT NOT NULL,
    datum DATE NOT NULL,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
);

CREATE TABLE tezina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    datum DATETIME DEFAULT CURRENT_TIMESTAMP,
    tezina_kg FLOAT NOT NULL,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
);
CREATE TABLE recepti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    naziv VARCHAR(100) NOT NULL,
    opis TEXT,
    datum DATE NOT NULL,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id)
);
CREATE TABLE recept_sastojci (
    id INT AUTO_INCREMENT PRIMARY KEY,
    recept_id INT NOT NULL,
    hrana_id INT,                    -- opcionalno, ako je iz opće baze hrane
    korisnicka_hrana_id INT,         -- opcionalno, ako je unos korisnika
    naziv VARCHAR(100),              -- koristi se ako hrana nije u bazi
    kolicina FLOAT NOT NULL,         -- u gramima
    kalorije FLOAT NOT NULL,
    proteini FLOAT NOT NULL,
    ugljikohidrati FLOAT NOT NULL,
    masti FLOAT NOT NULL,
    FOREIGN KEY (recept_id) REFERENCES recepti(id),
    FOREIGN KEY (hrana_id) REFERENCES hrana(id),
    FOREIGN KEY (korisnicka_hrana_id) REFERENCES korisnicka_hrana(id)
);
CREATE TABLE dnevni_unos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    korisnik_id INT NOT NULL,
    hrana_id INT,                  -- iz opće baze
    korisnicka_hrana_id INT,       -- ako je vlastita hrana
    kolicina FLOAT NOT NULL,       -- u gramima
    datum DATE NOT NULL,
    FOREIGN KEY (korisnik_id) REFERENCES korisnici(id),
    FOREIGN KEY (hrana_id) REFERENCES hrana(id),
    FOREIGN KEY (korisnicka_hrana_id) REFERENCES korisnicka_hrana(id)
);
