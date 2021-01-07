
# Cancello il database gestione_backup 
DROP DATABASE IF EXISTS gestione_backup;

#Creao il database gestione_backup (database che gestisce la gestione backup)
CREATE DATABASE IF NOT EXISTS gestione_backup;

USE gestione_backup;

# Cancello la tabella utenti
DROP TABLE IF EXISTS utenti;

# Creo la tabella utenti
CREATE TABLE IF NOT EXISTS utenti (
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome VARCHAR(255) NOT NULL,
    cognome VARCHAR(255) NOT NULL,
    username VARCHAR(255) NOT NULL,
    pswd VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    tipo ENUM('amministratore', 'responsabile'),
    immagineProfilo varchar(20) default "profilo.png" NOT NULL
);

# Creo l'utente Admin che è l'amministratore principale del applicativo web
INSERT INTO utenti(nome, cognome, username, pswd, email, tipo) 
VALUES ('Pierpaolo', 'Casati', 'Admin', 'Admin.123', 'pierpaolo.casati@samtrevano.ch', 'amministratore');
INSERT INTO utenti(nome, cognome, username, pswd, email, tipo) 
VALUES('Fabrizio', 'Valsangiacomo', 'fabrizio.valsangiacomo', 'Admin.123','fabrizio.valsangiacomo@edu.ti.ch', 'amministartore');