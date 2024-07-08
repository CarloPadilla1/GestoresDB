CREATE TABLE Auditoría (
    ID BIGINT AUTO_INCREMENT PRIMARY KEY,
    NombreTabla VARCHAR(255),
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UsuarioActual VARCHAR(255),
    DetalleAccion TEXT
);

DELIMITER //

CREATE PROCEDURE generar_disparadores()
BEGIN
    DECLARE done INT DEFAULT 0;
    DECLARE tabla VARCHAR(255);
    DECLARE cursor_tablas CURSOR FOR 
        SELECT table_name
        FROM information_schema.tables
        WHERE table_schema = DATABASE() AND table_name != 'Auditoría';

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cursor_tablas;

    bucle_tablas: LOOP
        FETCH cursor_tablas INTO tabla;
        IF done THEN
            LEAVE bucle_tablas;
        END IF;

        SET @sql = CONCAT('CREATE TRIGGER auditoria_delete_', tabla, ' AFTER DELETE ON ', tabla, '
            FOR EACH ROW
            BEGIN
                INSERT INTO Auditoría (NombreTabla, FechaHora, UsuarioActual, DetalleAccion)
                VALUES (\'', tabla, '\', CURRENT_TIMESTAMP, USER(), CONCAT(\'DELETE: \', OLD.*));
            END');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql = CONCAT('CREATE TRIGGER auditoria_insert_', tabla, ' AFTER INSERT ON ', tabla, '
            FOR EACH ROW
            BEGIN
                INSERT INTO Auditoría (NombreTabla, FechaHora, UsuarioActual, DetalleAccion)
                VALUES (\'', tabla, '\', CURRENT_TIMESTAMP, USER(), CONCAT(\'INSERT: \', NEW.*));
            END');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

        SET @sql = CONCAT('CREATE TRIGGER auditoria_update_', tabla, ' AFTER UPDATE ON ', tabla, '
            FOR EACH ROW
            BEGIN
                INSERT INTO Auditoría (NombreTabla, FechaHora, UsuarioActual, DetalleAccion)
                VALUES (\'', tabla, '\', CURRENT_TIMESTAMP, USER(), CONCAT(\'UPDATE: OLD: \', OLD.*, \' NEW: \', NEW.*));
            END');
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;

    END LOOP;

    CLOSE cursor_tablas;
END //

DELIMITER ;

CALL generar_disparadores();
