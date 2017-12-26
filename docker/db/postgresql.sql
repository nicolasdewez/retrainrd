DO
$body$
BEGIN
   IF NOT EXISTS (
      SELECT *
      FROM   pg_catalog.pg_user
      WHERE  usename = 'retrainrd'
   )
   THEN
      CREATE ROLE retrainrd SUPERUSER LOGIN;
   END IF;
END
$body$;
