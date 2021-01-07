
USE gestione_backup;


# Cancello la tabella db_link 
DROP TABLE IF EXISTS db_link;


#Creo la tabella db_link se esiste
CREATE TABLE IF NOT EXISTS db_link(
	id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
	dbname VARCHAR(60) NULL,
    dbhost VARCHAR(60) NULL,
    dbuser VARCHAR(60) NULL,
    dbpass VARCHAR(256) NULL,
    dbport VARCHAR(4) NULL,
    backupFrequency int default 0 NOT NULL,
    lastBackupTime datetime NOT NULL 
);




