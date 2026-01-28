ALTER TABLE gebruiker
ADD COLUMN role_id INT,
ADD CONSTRAINT fk_gebruiker_role
FOREIGN KEY (role_id) REFERENCES roles(id);
