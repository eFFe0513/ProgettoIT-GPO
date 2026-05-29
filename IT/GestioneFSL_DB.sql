Drop database if exists GestioneFSL;
Create database GestioneFSL;
Use GestioneFSL;

CREATE TABLE Azienda (
    PI VARCHAR (30) PRIMARY KEY,
    ragione_sociale VARCHAR (50),
    responsabile VARCHAR (50),
    email VARCHAR (50),
    settore VARCHAR (50),
    codice_ATECO VARCHAR (6), -- i codici ateco hanno massimo 6 cifre
    telefono VARCHAR (15)
);

CREATE TABLE Sede_operativa (
    ID_S VARCHAR (30) PRIMARY KEY,
    CAP INT,
    citta VARCHAR (50),
    provincia VARCHAR (50),
    indirizzo VARCHAR (100),
    PI VARCHAR (30),
    FOREIGN KEY (PI) REFERENCES Azienda(PI)
);

CREATE TABLE Sede_legale (
    ID_L VARCHAR (30) PRIMARY KEY,
    CAP INT,
    citta VARCHAR (50),
    provincia VARCHAR (50),
    indirizzo VARCHAR (100),
    PI VARCHAR (30),
    FOREIGN KEY (PI) REFERENCES Azienda(PI)
);

CREATE TABLE Tutor_aziendale (
    CF_TA VARCHAR (30) PRIMARY KEY,
    nome VARCHAR (50),
    cognome VARCHAR (50),
    inquadramento VARCHAR (50),
    competenze TEXT,
    esperienze TEXT,
    email VARCHAR (50),
    telefono VARCHAR (15)
);

CREATE TABLE Tutor_scolastico (
    CF_TS VARCHAR (30) PRIMARY KEY,
    nome VARCHAR (50),
    cognome VARCHAR (50)
);

CREATE TABLE Studente (
    CF_S VARCHAR (30) PRIMARY KEY,
    nome VARCHAR (50),
    cognome VARCHAR (50),
    data_nascita DATE,
    classe VARCHAR(10),
    indirizzo_studi VARCHAR(20),
    telefono VARCHAR (15),
    email VARCHAR (50),
    competenze TEXT,
    CF_TS VARCHAR (30),
    FOREIGN KEY (CF_TS) REFERENCES Tutor_scolastico(CF_TS)
);


CREATE TABLE Attivita (
    titolo VARCHAR (30) PRIMARY KEY,
    descrizione TEXT,
    periodo_i DATETIME,
    periodo_f DATETIME,
    periodo INT, -- durata in giorni
    orario_i TIME,
    orario_f TIME,
    att_oggetto VARCHAR (100),
    max_studenti INT,
    competenze_ric VARCHAR (100),
    ambito VARCHAR (50),
    PI VARCHAR (30),
    CF_TA VARCHAR (30),
    FOREIGN KEY (PI) REFERENCES Azienda(PI),
    FOREIGN KEY (CF_TA) REFERENCES Tutor_aziendale(CF_TA)
);

CREATE TABLE Partecipa (
    CF_S VARCHAR (30),
    titolo VARCHAR (30),
    PRIMARY KEY (CF_S, titolo),
    FOREIGN KEY (CF_S) REFERENCES Studente(CF_S),
    FOREIGN KEY (titolo) REFERENCES Attivita(titolo)
);

CREATE TABLE Commento (
    ID_C INT PRIMARY KEY,
    testo TEXT,
    CF_S VARCHAR(30),
    FOREIGN KEY (CF_S) REFERENCES Studente(CF_S)
);

-- Tabella per il salvataggio delle chiavi di accesso per ogni sessione
CREATE TABLE Chiavi (
    User_id VARCHAR (30) PRIMARY KEY,
    Tipo VARCHAR (30), -- Azienda o Studente
    Password_cod VARCHAR (30)
)