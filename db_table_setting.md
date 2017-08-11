CREATE TABLE kdb (
name varchar(32) NOT NULL,
regisid varchar(15) NOT NULL,
sex varchar(1) NOT NULL,
age DECIMAL(3,1) NOT NULL,
region varchar(32) NOT NULL,
vote_date datetime,
vote_chk boolean NOT NULL default '0',
PRIMARY KEY (regisId)
);
