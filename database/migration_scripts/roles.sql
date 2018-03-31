/* Insert Jan as Musikalischer Leiter
*/
INSERT INTO `intern`.`intern_role_user`(
	`user_id`, `role_id`
)
SELECT
	`id`, 3
FROM
	`intern`.`intern_users`
WHERE
	`last_name` = "Herrmann";

/* Retrieve Vorstand and Stimmfuehrer
*/
INSERT INTO `intern`.`intern_role_user`(
	`user_id`, `role_id`
)
SELECT
	`intern_users`.`id`,
	IF(`voice_leader` = 1, 2, 1)
FROM
	`old_intern`.`users` JOIN `intern`.`intern_users` ON `old_intern`.`users`.`surname` = `intern`.`intern_users`.`last_name`
WHERE
	(`right_id` <> 0 OR `voice_leader` <> 0)
	AND
	`last_logged_in` >= "2017-10-01 00:00:00";