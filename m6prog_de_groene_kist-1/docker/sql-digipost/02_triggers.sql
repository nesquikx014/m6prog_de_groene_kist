-- Trigger to ensure users.token is set to a unique UUID when not provided
USE m6prog_digipost;

DELIMITER $$
CREATE TRIGGER users_before_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
  IF NEW.token IS NULL OR NEW.token = '' THEN
    SET NEW.token = UUID();
  END IF;
END$$
DELIMITER ;
