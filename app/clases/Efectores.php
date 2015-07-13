<?php
class Efectores {
	private $_db, $_efector = ['cuie', 'siisa', 'nombre', 'domicilio', 'codigo_postal', 'tipo_efector', 'rural', 'cics', 'categorizacion', 'dependencia_adm', 'dependencia_san', 'integrante', 'compromiso', 'priorizado', 'referente', 'numero_compromiso', 'firmante_compromiso', 'fecha_sus_cg', 'fecha_ini_cg', 'fecha_fin_cg', 'pago_indirecto', 'numero_convenio', 'firmante_convenio', 'fecha_sus_ca', 'fecha_ini_ca', 'fecha_fin_ca', 'nombre_adm', 'codigo_adm', 'provincia', 'ciudad', 'departamento', 'localidad', 'email', 'email_observaciones', 'telefono', 'telefono_observaciones'];
	public

	function __construct()
	{
		$this->_db = Bdd::getInstance();
	}

	public

	function getEfectoresProvincia($id_provincia)
	{
		return $this->_db->fquery('getEfectoresProvincia', [$id_provincia], FALSE)->get()['c'];
	}

	public

	function getEfectoresCompromisoProvincia($id_provincia)
	{
		return $this->_db->fquery('getEfectoresCompromisoProvincia', [$id_provincia], FALSE)->get()['c'];
	}

	public

	function getDescentralizacion($id_provincia)
	{
		return $this->_db->fquery('getDescentralizacion', [$id_provincia], FALSE)->get()['d'];
	}

	public

	function listar($post)
	{
		if (strlen($post['search']['value']))
		{
			$sql    = 'listar_filtrado';
			$params = ['%'.$post['search']['value'].'%', '%'.$post['search']['value'].'%', '%'.$post['search']['value'].'%', $post['length'], $post['start']];
		}
		else
		{
			$sql    = 'listar';
			$params = [$post['length'], $post['start']];
		}

		$data = $this->_db->fquery($sql, $params, FALSE)->getResults();
		foreach ($data as $key => $value)
		{
			$json['data'][$key] = $value;
		}

		$json['recordsFiltered'] = $this->_db->findCount('efectores.efectores', ['id_estado in (?,?)', [1, 4]]);
		$json['recordsTotal'] = $this->_db->findCount('efectores.efectores', ['id_estado in (?,?)', [1, 4]]);
		$json['draw'] = $post['draw']++;
		return (json_encode($json));
	}

	public

	function getEfector($id_efector)
	{
		return $this->_db->fquery('getEfector', [$id_efector], FALSE)->getResults()[0];
	}

	public function getEfectorBySiisaOCuie($efector)
	{
		$sql = " SELECT cuie, id_provincia FROM efectores.efectores e INNER JOIN efectores.datos_geograficos dg ON e.id_efector = dg.id_efector WHERE cuie = :efector OR siisa = :efector ";

		return $this->_db->aquery($sql, ['efector' => $efector])->getResults();
	}

	public

	function getEfectorGeo($id_efector)
	{
		return $this->_db->fquery('getEfectorGeo', [$id_efector], FALSE)->getResults()[0];
	}

	public

	function getEfectorCompromiso($id_efector)
	{
		$data = $this->_db->findAll('efectores.compromiso_gestion', ['id_efector = ?', [$id_efector]]);
		if ( ! $this->_db->getCount())
		{
			$data['numero_compromiso'] = '-';
			$data['firmante'] = '-';
			$data['pago_indirecto'] = '-';
			$data['fecha_suscripcion'] = '-';
			$data['fecha_inicio'] = '-';
			$data['fecha_fin'] = '-';
		}

		return $data;
	}

	public

	function getEfectorConvenio($id_efector)
	{
		$data = $this->_db->findAll('efectores.convenio_administrativo', ['id_efector = ?', [$id_efector]]);
		if ( ! $this->_db->getCount())
		{
			$data['numero_compromiso'] = '-';
			$data['firmante'] = '-';
			$data['nombre_tercer_administrador'] = '-';
			$data['codigo_tercer_administrador'] = '-';
			$data['fecha_suscripcion'] = '-';
			$data['fecha_inicio'] = '-';
			$data['fecha_fin'] = '-';
		}

		return $data;
	}

	public

	function getEfectorReferente($id_efector)
	{
		return $this->_db->find('nombre', 'efectores.referentes', ['id_efector = ?', [$id_efector]]);
	}

	public

	function getEfectorDescentralizacion($id_efector)
	{
		return $this->_db->findAll('efectores.descentralizacion', ['id_efector = ?', [$id_efector]]);
	}

	public

	function getPrestaciones($id_efector)
	{
		return $this->_db->findCount('prestaciones.prestaciones', ['efector = (select cuie from efectores.efectores where id_efector = ?)', [$id_efector]]);
	}

	public

	function getBeneficiariosInscriptos($id_efector)
	{
		$sql = "select
  count (*)
from
  beneficiarios.beneficiarios_periodos
where
  efector_asignado = (select cuie from efectores.efectores where id_efector = ?)
  and periodo = (select max (periodo) from beneficiarios.beneficiarios_periodos)";
		return $this->_db->query($sql, [$id_efector])->getResults()[0]['count'];
	}

	public

	function getPrestacionesPriorizadas($id_efector)
	{
		$sql = "
          select count (*) as c
          from
             prestaciones.prestaciones
          where
            efector = (select cuie from efectores.efectores where id_efector = ?)
            and codigo_prestacion in (select codigo_prestacion from pss.codigos_priorizadas)";
		return $this->_db->query($sql, [$id_efector])->getResults()[0]['c'];
	}

	public

	function getBeneficiariosCeb($id_efector)
	{
		return $this->_db->fquery('getBeneficiariosCeb', [$id_efector], FALSE)->getResults()[0]['c'];
	}

	public

	function descargarTabla()
	{
		$file = 'efectores.csv';
		$ruta = '../data/padrones/'.$file;
		$data = $this->_db->fquery('descargarTabla')->getResults();
		$encabezados = array_keys($data[0]);
		unlink($ruta);
		file_put_contents($ruta, implode("\t", $encabezados)."\r\n", FILE_APPEND);
		foreach ($data as $index => $valor)
		{
			file_put_contents($ruta, html_entity_decode(implode("\t", $valor), ENT_QUOTES, 'UTF-8')."\r\n", FILE_APPEND);
		}
	}

	public

	function efectorJson($busqueda)
	{
		$params = [$busqueda.'%', $busqueda.'%'];
		echo json_encode($this->_db->fquery('efectorJson', $params, TRUE)->getList());
	}

	public

	function getInfoBaja($cuie)
	{
		return $this->_db->findAll('efectores.efectores', ['cuie = ?', [$cuie]]);
	}

	public

	function baja($cuie)
	{
		$sql    = "update efectores.efectores set id_estado                                                                                                                                                                                                                                                                                                                                                                                  = 3 where cuie                                                                                                                                                                                                                                                                                                                                                                                  = ?";
		$params = [$cuie];
		if ( ! $this->_db->query($sql, $params)->getError())
		{
			echo 'Se ha soliciado la baja del efector '.$cuie.', estar&aacute; a revisi&oacute;n del &aacute;rea C&aacute;pitas.';
		}
		else
		{
			echo 'Ha ocurrido un error.';
		}
	}

	public

	function getSiisa($jurisdiccion)
	{
		$sql = "
        select '99999999' || '{$jurisdiccion}' || lpad ((max (substring (siisa from 11 for 4)) :: numeric + 1 ) :: varchar , 4 , '0') as siisa
        from
          efectores.efectores
        where
          substring (siisa from 1 for 8) = '99999999'
          and substring (siisa from 9 for 2) = ?";
		$this->_db->query($sql, [$jurisdiccion]);
		if ($this->_db->getCount())
		{
			echo $this->_db->get()['siisa'];
		}
		else
		{
			echo '99999999'.$jurisdiccion.'0001';
		}
	}

	public

	function selectTipoEfector()
	{
		$sql    = "select * from efectores.tipo_efector where id_tipo_efector <> 9";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_tipo_efector']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public

	function selectCategorizacion()
	{
		$sql    = "select * from efectores.tipo_categorizacion where id_categorizacion <> 10";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_categorizacion']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public function selectTipoTelefono()
	{
		$sql  = "select * from efectores.tipo_telefono order by id_tipo_telefono";
		$data = $this->_db->query($sql)->getResults();

		$select = '';

		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_tipo_telefono']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public

	function selectDependencia()
	{
		$sql    = "select * from efectores.tipo_dependencia_administrativa where id_dependencia_administrativa <> 5";
		$data   = $this->_db->query($sql)->getResults();
		$select = '';
		foreach ($data as $key => $value)
		{
			$select .= "<option value='{$value['id_dependencia_administrativa']}'>{$value['descripcion']}</option>";
		}

		return $select;
	}

	public function sanear($string)
	{
		return htmlentities(pg_escape_string($string), ENT_QUOTES, 'UTF-8');
	}

	public function getNewCuie()
	{
		parse_str($_POST['p'], $data);

		$sql = "select
						substring (max (cuie) from 1 for 1) || lpad ((substring (max (cuie) from 2 for 5) :: int + 1) :: text , 5 , '0') :: varchar as cuie
					from (
						select
							*
						from
							efectores.efectores e left join
							efectores.datos_geograficos d on e.id_efector = d.id_efector
						where substring (cuie from 2 for 5) :: int <> 99999 ) e
					where id_provincia = '".$data['provincia']."'";

		$row = $this->_db->query($sql)->getResults();

		echo json_encode($row[0]);
	}

	public

	function alta()
	{
		parse_str($_POST['p'], $data);

		//$data = (array_combine($this->_efector, func_get_args()));
		//print_r($data);

		$data['siisa'] = $this->sanear($data['siisa']);
		$data['nombre'] = $this->sanear($data['nombre']);
		$data['domicilio'] = $this->sanear($data['domicilio']);
		$data['codigo_postal'] = $this->sanear($data['codigo_postal']);
		$data['dependencia_san'] = $this->sanear($data['dependencia_san']);
		$data['fecha_envio'] = $this->sanear($data['fecha_envio']);

		$efector = array(
			$data['cuie']
			, $data['siisa']
			, $data['nombre']
			, $data['domicilio']
			, $data['codigo_postal']
			, $data['tipo_efector']
			, $data['rural']
			, $data['cics']
			, $data['categorizacion']
			, $data['dependencia_adm']
			, $data['dependencia_san']
			, $data['integrante']
			, $data['compromiso']
			, $data['priorizado']
			//, $_POST['sumar']
			, '2',
		);

		$bool_resultado = false;

		$sql = " INSERT INTO efectores.efectores (cuie , siisa , nombre , domicilio , codigo_postal , id_tipo_efector , rural , cics , id_categorizacion , id_dependencia_administrativa , dependencia_sanitaria , integrante , compromiso_gestion , priorizado , id_estado)
				values ('".implode("','", $efector)."'); ";

		if ( ! $this->_db->query($sql)->getError())
		{
			$sql = " SELECT currval ('efectores.efectores_id_efector_seq') limit 1 ";
			$id_efector = $this->_db->query($sql)->getResults()[0]['currval'];

			if ($id_efector)
			{

				// Datos geográficos
				$geograficos = array(
					$id_efector
					, $data['provincia']
					, $data['departamento']
					, $data['localidad']
					, $this->sanear($data['ciudad']),
				);

				$sql = "
			insert into efectores.datos_geograficos (id_efector , id_provincia , id_departamento , id_localidad , ciudad)
			values ('".implode("','", $geograficos)."');";

				$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;

				// Referente
				$referente = array(
					$id_efector
					, $this->sanear($data['referente']),
				);

				$sql = "
			insert into efectores.referentes (id_efector , nombre)
			values ('".implode("','", $referente)."');";

				$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;

				// Teléfono
				$telefono = array(
					$id_efector
					, $this->sanear($data['telefono'])
					, $data['tipo_telefono']
					, $this->sanear($data['telefono_observaciones']),
				);

				$sql = "
			insert into efectores.telefonos (id_efector , numero_telefono , id_tipo_telefono , observaciones)
			values ('".implode("','", $telefono)."');";

				$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;

				// Email
				$email = array(
					$id_efector
					, $this->sanear($data['email'])
					, $this->sanear($data['email_observaciones']),
				);

				$sql = "
			insert into efectores.email (id_efector , email , observaciones)
			values ('".implode("','", $email)."');";

				$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;

				if ($data['integrante'] == 'S' && $data['compromiso'] == 'S')
				{

					// Compromiso gestión
					$compromiso = array(
						$id_efector
						, $this->sanear($data['numero_compromiso'])
						, $this->sanear($data['firmante_compromiso'])
						, $data['fecha_sus_cg']
						, $data['fecha_ini_cg']
						, $data['fecha_fin_cg']
						, $data['pago_indirecto'],
					);
					$sql = "
				insert into efectores.compromiso_gestion
				values ('".implode("','", $compromiso)."');";

					$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;

					if ($data['pago_indirecto'] == 'S')
					{

						// Convenio tercer administrador
						$convenio = array(
							$id_efector
							, $this->sanear($data['numero_convenio'])
							, $this->sanear($data['firmante_convenio'])
							, $this->sanear($data['nombre_adm'])
							, $this->sanear($data['codigo_adm'])
							, $data['fecha_sus_ca']
							, $data['fecha_ini_ca']
							, $data['fecha_fin_ca'],
						);

						$sql = "
					insert into efectores.convenio_administrativo
					values ('".implode("','", $convenio)."')";

						$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;
					}
				}

				if ( ! $bool_resultado)
				{

					$sql = "
				insert into efectores.operaciones (id_efector , id_usuario_operacion , fecha_operacion , tipo_operacion , observaciones)
				values ('$id_efector' , $_SESSION[id_usuario] , localtimestamp , 1 , 'PEDIDO ALTA DE EFECTOR')";

					$bool_resultado = $this->_db->query($sql)->getError() || $bool_resultado;
					if ( ! $bool_resultado)
					{

						//$mails = array('gdhekel@gmail.com', 'javier.minsky@gmail.com', 'mrgutierrez@msal.gov.ar', 'rodrigoplansumar@gmail.com');
						$mails = array('gdhekel@gmail.com', 'javier.minsky@gmail.com', 'rodrigoplansumar@gmail.com');

						$email = new Email();
						$email->enviar_mail(['subject' => 'Pedido de alta de nuevo efector', 'emailTo' => $mails, 'message' => 'Efector: '.$data['cuie'], 'fecha' => $data['fecha_envio'], 'ajax' => 'true']);
						//email('Pedido de alta de nuevo efector', 'gdhekel@gmail.com,javier.minsky@gmail.com,mrgutierrez@msal.gov.ar');
						echo "Alta de efector solicitada";
					}
					else
					{
						$this->_db->query(" DELETE from efectores.efectores where id_efector = $id_efector")->getResults();
						echo "Error al solicitar el alta del efector 2";
					}
				}
			}
		}
		else
		{
			echo "Error al solicitar el alta del efector";
		}

	}

	public function getSugerenciaEfectores($efector)
	{
		$sql = "select cuie from efectores.efectores where (cuie ilike '$efector%' OR siisa ilike '$efector%') AND id_estado = 1 limit 10";
		return $this->_db->query($sql)->getResults();
	}

	public function generales($efector)
	{
		switch (strlen($efector))
		{
			case 6:$campo = 'cuie';
				break;
			case 14:$campo = 'siisa';
				break;
		}

		$sql = 'select cuie
				, siisa
				, nombre
				, domicilio
				, codigo_postal
				, id_tipo_efector
				, rural
				, cics
				, id_categorizacion
				, id_dependencia_administrativa
				, dependencia_sanitaria
				, integrante
				, compromiso_gestion
				, priorizado
				, ppac
				, codigo_provincial_efector
				--, sumar
			from
				efectores.efectores
			where '.$campo.' = ? and id_estado = 1';

		return $this->_db->query($sql, [$efector])->getResults();
	}

}
