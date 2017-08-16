create table voter (
id varchar(256) NOT NULL,
addr varchar(34) NOT NULL,
vote_chk boolean NOT NULL default '0',
vote_date datetime,
primary key(id)
);

create table candi (
id varchar(256) NOT NULL,
addr varchar(34) NOT NULL,
regis_date datetime,
primary key(id)
);
