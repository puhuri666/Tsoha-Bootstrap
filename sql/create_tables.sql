CREATE TABLE bettor (
    id SERIAL PRIMARY KEY,
    firstname character varying(50) NOT NULL,
    lastname character varying(50) NOT NULL,
    address character varying(150) NOT NULL,
    zipcode character varying(20) NOT NULL,
    town character varying(50) NOT NULL,
    email character varying(50) NOT NULL,
    password character varying(50) NOT NULL,
    balance double precision DEFAULT 0.0 NOT NULL,
    superuser boolean DEFAULT false NOT NULL
);



CREATE TABLE team (
    id SERIAL PRIMARY KEY,
    sport varchar(50) NOT NULL,
    league varchar(100) NOT NULL,
    name varchar(200) NOT NULL
);


CREATE TABLE matchup (
    id SERIAL PRIMARY KEY,
    startdate character varying(50) NOT NULL,
    scorehome integer DEFAULT 0 NOT NULL,
    scoreaway integer DEFAULT 0 NOT NULL,
    betting_result character varying(15),
    hometeam integer REFERENCES team NOT NULL,
    awayteam integer REFERENCES team NOT NULL
);

CREATE TABLE wager (
    id SERIAL PRIMARY KEY,
    bettor integer REFERENCES bettor NOT NULL,
    matchup integer REFERENCES matchup NOT NULL,
    betting_choice character varying(1) NOT NULL,
    betting_amount double precision NOT NULL,
    betting_odds double precision,
    settled boolean DEFAULT false,
    result character varying(15) DEFAULT 'ei tulosta'::character varying,
    return double precision,
    id integer NOT NULL
);

CREATE TABLE current_odds (
    matchup integer REFERENCES matchup NOT NULL,
    hometeam double precision NOT NULL,
    draw double precision,
    awayteam double precision NOT NULL
);


