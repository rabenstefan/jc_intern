/* Get all users, that have logged in since 01.10.2017
*/
INSERT INTO `intern`.`intern_users`(
    `first_name`,
    `last_name`,
    `email`,
    `password`,
    `birthday`,
    `phone`,
    `address_street`,
    `address_zip`,
    `address_city`,
    `voice_id`,
    `last_echo`
)
SELECT
    `forename`,
    `surname`,
    `email`,
    `surname`,
    `date_of_birth`,
    CONCAT(`mobil_prefix`, `mobil_number`),
    CONCAT(`street`, ' ', `street_number`),
    `plz`,
    `city`,
    IF(`voice_id` + 4 > 5, `voice_id` + 4, 1),
    1
FROM
    `old_intern`.`users`
WHERE
	`last_logged_in` >= "2017-10-01 00:00:00"