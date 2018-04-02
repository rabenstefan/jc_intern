INSERT INTO `intern`.`intern_rehearsals` (`start`, `end`, `title`, `description`, `semester_id`)

SELECT
	`dateTimeBegin`,
	IF(`dateTimeEnd` = "0000-00-00 00:00:00", `dateTimeBegin`, `dateTimeEnd`),
	`name`,
	`description`,
	2
FROM
	`old_intern`.events
WHERE
	`rehearsal` = 1 AND
	`dateTimeBegin` >= "2018-04-01 00:00:00" AND
	`deleted` <> 1;

INSERT INTO `intern`.`intern_gigs` (`start`, `end`, `title`, `description`, `semester_id`)

SELECT
	`dateTimeBegin`,
	IF(`dateTimeEnd` = "0000-00-00 00:00:00", `dateTimeBegin`, `dateTimeEnd`),
	`name`,
	`description`,
	2
FROM
	`old_intern`.events
WHERE
	`rehearsal` = 0 AND
	`dateTimeBegin` >= "2018-04-01 00:00:00" AND
	`deleted` <> 1;

INSERT INTO `intern`.`intern_gig_attendances` (`user_id`, `gig_id`, `attendance`, `comment`)
SELECT
    `intern`.`intern_users`.`id`,
    `intern`.`intern_gigs`.`id`,
    `old_intern`.`users_events`.`status_id` - 1,
    `old_intern`.`users_events`.`comment`
FROM
    `old_intern`.`users_events`
	INNER JOIN (
	        `old_intern`.`events`
	    	INNER JOIN `intern`.`intern_gigs` ON
	        	`old_intern`.`events`.`dateTimeBegin` = `intern`.`intern_gigs`.`start`
	    )
	ON
	    `old_intern`.`events`.`event_id` = `old_intern`.`users_events`.`event_id`
	INNER JOIN (
	        `old_intern`.`users`
	    	INNER JOIN `intern`.`intern_users` ON
	        	`old_intern`.`users`.`surname` = `intern`.`intern_users`.`last_name`
		)
	ON
	    `old_intern`.`users`.`user_id` = `old_intern`.`users_events`.`user_id`
WHERE
    `old_intern`.`events`.`rehearsal` = 0 AND `old_intern`.`events`.`dateTimeBegin` >= "2018-04-01 00:00:00" AND `old_intern`.`events`.`deleted` <> 1 AND `old_intern`.`users_events`.`status_id` > 0