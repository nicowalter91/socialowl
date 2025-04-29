CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('follow', 'comment', 'like') NOT NULL,
    content TEXT NOT NULL,
    post_id INT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Add post_id column if it doesn't exist
ALTER TABLE notifications
ADD COLUMN IF NOT EXISTS post_id INT,
ADD FOREIGN KEY IF NOT EXISTS (post_id) REFERENCES posts(id) ON DELETE CASCADE;