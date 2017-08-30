CREATE TABLE kdb (
name varchar(32) NOT NULL,
regisid varchar(15) NOT NULL,
sex varchar(1) NOT NULL,
age DECIMAL(3,1) NOT NULL,
region varchar(32) NOT NULL,
pubkey varchar(1024),
login_data datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
manager varchar(1) NOT NULL DEFAULT '0',
candidate varchar(1) NOT NULL DEFAULT '0',
PRIMARY KEY (regisId)
);
