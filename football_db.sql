CREATE TABLE login(
id		int(11) NOT NULL,
username	varchar(30) NOT NULL UNIQUE,
password	varchar(50) NOT NULL,
player_1	varchar(30) NOT NULL,
player_2	varcahr(30) NOT NULL,
team_name	varchar(30) NOT NULL UNIQUE,
score 		int(11) NOT NULL DEFAULT 0,
match_win	int(11) NOT NULL DEFAULT 0,
match_lost	int(11) NOT NULL DEFAULT 0,
PRIMARY KEY(id)
);
