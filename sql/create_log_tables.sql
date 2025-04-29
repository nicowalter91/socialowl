-- Tabelle für Edit-Logs
CREATE TABLE IF NOT EXISTS edit_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('post', 'comment') NOT NULL,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabelle für Deletion-Logs
CREATE TABLE IF NOT EXISTS deletion_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('post', 'comment') NOT NULL,
    item_id INT NOT NULL,
    user_id INT NOT NULL,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Trigger für Post-Bearbeitungen
DELIMITER //
CREATE TRIGGER after_post_update
AFTER UPDATE ON posts
FOR EACH ROW
BEGIN
    INSERT INTO edit_log (type, item_id, user_id)
    VALUES ('post', NEW.id, NEW.user_id);
END//

-- Trigger für Kommentar-Bearbeitungen
CREATE TRIGGER after_comment_update
AFTER UPDATE ON comments
FOR EACH ROW
BEGIN
    INSERT INTO edit_log (type, item_id, user_id)
    VALUES ('comment', NEW.id, NEW.user_id);
END//

-- Trigger für Post-Löschungen
CREATE TRIGGER before_post_delete
BEFORE DELETE ON posts
FOR EACH ROW
BEGIN
    INSERT INTO deletion_log (type, item_id, user_id)
    VALUES ('post', OLD.id, OLD.user_id);
END//

-- Trigger für Kommentar-Löschungen
CREATE TRIGGER before_comment_delete
BEFORE DELETE ON comments
FOR EACH ROW
BEGIN
    INSERT INTO deletion_log (type, item_id, user_id)
    VALUES ('comment', OLD.id, OLD.user_id);
END//
DELIMITER ; 