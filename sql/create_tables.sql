CREATE TABLE bettor (
    id SERIAL PRIMARY KEY,
    firstname varchar(50) NOT NULL,
    lastname varchar(50) NOT NULL,
    address varchar(150) NOT NULL,
    zipcode varchar(20) NOT NULL,
    town varchar(50) NOT NULL,
    email varchar(50) NOT NULL,
    password varchar(50) NOT NULL,
    balance float NOT NULL DEFAULT 0.0,
    superuser boolean DEFAULT FALSE NOT NULL
);


CREATE TABLE suggestion (
    id SERIAL PRIMARY KEY,
    suggestion text NOT NULL,
    bettor integer REFERENCES bettor
);

CREATE TABLE team (
    id SERIAL PRIMARY KEY,
    sport varchar(50) NOT NULL,
    league varchar(100) NOT NULL,
    name varchar(200) NOT NULL
);


CREATE TABLE matchup (
    id SERIAL PRIMARY KEY,
    startdate varchar(50) NOT NULL,
    scorehome integer DEFAULT 0 NOT NULL,
    scoreaway integer DEFAULT 0 NOT NULL,
    result varchar(10),
    betting_result varchar(1)
);

CREATE TABLE matchup_team (
    matchup integer REFERENCES matchup,
    team integer REFERENCES team,
    hometeam boolean DEFAULT true
);

CREATE TABLE wager (
    bettor integer REFERENCES bettor NOT NULL,
    matchup integer REFERENCES matchup NOT NULL,
    betting_choice varchar(1) NOT NULL,
    betting_amount float NOT NULL
);
