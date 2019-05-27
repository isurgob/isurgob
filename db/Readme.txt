
1. Ejecutar el primer script "a-pgbackup_sam_logico-2019-05.sql" mediante el comando:

  psql -h <<host>> -p <<port>> -U postgres -d postgres -W -f "a-pgbackup_sam_logico-2019-05.sql"

2. Ejecutar el segundo script de la misma manera:

  psql -h <<host>> -p <<port>> -U postgres -d "isurgob" -W -f "b-pgbackup_sam_dataaux-2019-05.sql" 

DONDE:
	<<host>> es el servidor POSTGRES.
	<<port>> es el puerto de acceso.
