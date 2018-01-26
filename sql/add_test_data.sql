INSERT INTO bettor(firstname, lastname, address, zipcode, town, email, password, superuser)
VALUES('Petteri', 'Markkola', 'Ouninpohjankummuntie 5', '00100', 'Helsinki', 'petteri.markkola@hki.fi', 'Apinaorkesteri5', TRUE);

INSERT INTO bettor(firstname, lastname, address, zipcode, town, email, password, balance)
VALUES('Salama', 'Härri', 'Liskotie 5 D 4', '00150', 'Helsinki', 'salamahaerri@sposti.fi', 'meikanpassu', 139.5);

INSERT INTO bettor(firstname, lastname, address, zipcode, town, email, password, balance)
VALUES('Mc', 'Megaputti', 'Märssytie 1 F 6', '90560', 'Oulu', 'mega@putti.fi', 'PassW0rdZ', 880.4);

INSERT INTO bettor(firstname, lastname, address, zipcode, town, email, password, balance)
VALUES('Raivo', 'Raimo', 'Kirkkokatu 16 B 12', '80100', 'Joensuu', 'raivo@raipe.fi', 'zSKALsaa2', 1233.50);

INSERT INTO team(name, sport, league) VALUES('Kärpät', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('HIFK', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('Tappara', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('Ilves', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('Lukko', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('HPK', 'hockey', 'smliiga');
INSERT INTO team(name, sport, league) VALUES('Florida', 'hockey', 'nhl');
INSERT INTO team(name, sport, league) VALUES('Anaheim', 'hockey', 'nhl');
INSERT INTO team(name, sport, league) VALUES('NY Islanders', 'hockey', 'nhl');
INSERT INTO team(name, sport, league) VALUES('NY Rangers', 'hockey', 'nhl');
INSERT INTO team(name, sport, league) VALUES('Minnesota', 'hockey', 'nhl');
INSERT INTO team(name, sport, league) VALUES('Tampa Bay', 'hockey', 'nhl');

INSERT INTO suggestion(suggestion, bettor)
VALUES ('laittakaa futistakin, senkin kendojanarit!!!!', 3);

INSERT INTO matchup(startdate, scorehome, scoreaway, result, betting_result)
VALUES('2018-01-26', 5, 1, '5-1', 1);
INSERT INTO matchup(startdate, scorehome, scoreaway, result, betting_result)
VALUES('2018-01-24', 5, 6, '5-6', 2);
INSERT INTO matchup(startdate, scorehome, scoreaway, result, betting_result)
VALUES('2018-01-23', 4, 9, '4-9', 1);

INSERT INTO matchup_team(matchup, team)
VALUES(1, 1);
INSERT INTO matchup_team(matchup, team, hometeam)
VALUES(1, 2, FALSE);

INSERT INTO matchup_team(matchup, team)
VALUES(2, 3);
INSERT INTO matchup_team(matchup, team, hometeam)
VALUES(2, 4, FALSE);

INSERT INTO matchup_team(matchup, team)
VALUES(2, 5);
INSERT INTO matchup_team(matchup, team, hometeam)
VALUES(2, 6, FALSE);

INSERT INTO wager(bettor, matchup, betting_choice, betting_amount)
VALUES (4, 2, 'X', 250);

INSERT INTO wager(bettor, matchup, betting_choice, betting_amount)
VALUES (3, 1, '1', 550.65);

