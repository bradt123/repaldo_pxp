CREATE OR REPLACE FUNCTION orga.f_url_foto (
  p_id_funcionario integer,
  p_lista_doc varchar
)
RETURNS varchar AS
$body$
DECLARE

v_url		varchar;

BEGIN

	if(position('FOTO_FUNCIONARIO' in p_lista_doc)>0)then
    	select (tar.nombre_archivo||'.'||tar.extension)::varchar
        into v_url
        from orga.tfuncionario tf
        left join param.tarchivo tar on tar.id_tabla = tf.id_funcionario and tar.id_tipo_archivo = 10
        where tf.id_funcionario = p_id_funcionario;
    end if;

  return v_url;

END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
COST 100;