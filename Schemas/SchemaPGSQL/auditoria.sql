CREATE TABLE Auditoría (
    ID SERIAL PRIMARY KEY,
    NombreTabla VARCHAR(255),
    FechaHora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UsuarioActual VARCHAR(255),
    DetalleAccion TEXT
);

CREATE OR REPLACE FUNCTION registrar_auditoria() RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO Auditoría (NombreTabla, FechaHora, UsuarioActual, DetalleAccion)
    VALUES (
        TG_TABLE_NAME,
        CURRENT_TIMESTAMP,
        CURRENT_USER,
        CASE TG_OP
            WHEN 'DELETE' THEN 'DELETE: ' || OLD::TEXT
            WHEN 'INSERT' THEN 'INSERT: ' || NEW::TEXT
            WHEN 'UPDATE' THEN 'UPDATE: OLD: ' || OLD::TEXT || ' NEW: ' || NEW::TEXT
        END
    );
    RETURN NULL;
END;
$$ LANGUAGE plpgsql;

DO $$ 
DECLARE
    rec RECORD;
BEGIN
    FOR rec IN
        SELECT tablename
        FROM pg_tables
        WHERE schemaname = 'public' AND tablename != 'auditoría'
    LOOP
        EXECUTE format('
            CREATE TRIGGER auditoria_delete
            AFTER DELETE ON %I
            FOR EACH ROW EXECUTE FUNCTION registrar_auditoria();
        ', rec.tablename);

        EXECUTE format('
            CREATE TRIGGER auditoria_insert
            AFTER INSERT ON %I
            FOR EACH ROW EXECUTE FUNCTION registrar_auditoria();
        ', rec.tablename);

        EXECUTE format('
            CREATE TRIGGER auditoria_update
            AFTER UPDATE ON %I
            FOR EACH ROW EXECUTE FUNCTION registrar_auditoria();
        ', rec.tablename);
    END LOOP;
END $$;
