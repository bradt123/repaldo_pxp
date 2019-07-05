CREATE OR REPLACE FUNCTION orga.final_palabra (
  texto varchar
)
RETURNS varchar AS
$body$
DECLARE
reco      				varchar;
v_iniciales   			varchar;
resp      				varchar;
resp1      				varchar;
resp2      				varchar;
espacio     			varchar;
result      			varchar;
resul					varchar;
v_i						integer;
total					varchar;
linea					varchar;
data					varchar;
data1 					varchar;
v_n 					integer;
BEGIN
v_n = orga.f_punto(texto);
if v_n = 0 then 
resul = texto;
else
v_iniciales = regexp_replace(regexp_replace(texto, E'<.*?>', '', 'g' ), E'&nbsp;', '', 'g'); 
espacio = regexp_replace(v_iniciales,'[[:space:]][[:space:]]',' ','g');
resp = REGEXP_REPLACE(espacio,'[[:digit:]]. ','','g');
resp1 = REGEXP_REPLACE(resp,'[[:digit:]].','','g');

v_i = orga.f_punto(resp1);

if v_i > 1 then 
      result = replace(resp1,'. ','.');
   	  linea = replace(replace(result,chr(10),''),chr(13),'');
      data = trim(linea);
      data1 = replace(data,'. ','.');
      resul = replace(data1,'.',chr(10));

elsif v_i = 1 then
	 total = replace(resp1,'.','');
     --total = replace(replace(replace(replace(resp1,chr(10),' '),chr(11),''),chr(13),''),chr(27),'');
     resul = total;
elsif v_i = 0 then 
      total = replace(replace(replace(resp1,chr(11),''),chr(13),''),chr(27),'');
	  resul = total;
end if;
end if;         
return resul;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;