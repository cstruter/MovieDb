CREATE TABLE IF NOT EXISTS `movies` (
	`movieId` int(11) NOT NULL,
	`imdbId` varchar(50) NOT NULL,
	`title` varchar(255) NOT NULL,
	`year` int(11) NOT NULL,
	`plot` text NOT NULL,
	`rating` varchar(50) NOT NULL,
	`runtime` varchar(50) NOT NULL,
	`released` varchar(50) NOT NULL,
	`genre` varchar(255) NOT NULL,
	`awards` varchar(255) NOT NULL,
	`poster` varchar(320) NOT NULL,
	`fbId` bigint(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

ALTER TABLE `movies`
	ADD PRIMARY KEY (`movieId`), 
	ADD UNIQUE KEY `imdbId` (`imdbId`), 
	ADD KEY `title` (`title`);
	
ALTER TABLE `movies`
	MODIFY `movieId` int(11) NOT NULL AUTO_INCREMENT;