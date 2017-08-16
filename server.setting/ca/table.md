CREATE TABLE kdb (
name varchar(32) NOT NULL,
regisid varchar(15) NOT NULL,
sex varchar(1) NOT NULL,
age DECIMAL(3,1) NOT NULL,
region varchar(32) NOT NULL,
login_date datetime,
pubkey varchar(1024) NOT NULL,
PRIMARY KEY (regisId)
);
