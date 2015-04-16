UPDATE ddjj.doiu9
			SET motivo_reimpresion = '"	 . pg_escape_string(htmlentities($_POST['motivo_reimpresion'], ENT_QUOTES, 'UTF-8')) . "'
			where
				id_provincia = '"	 . $_SESSION['grupo'] . "'
				and periodo_reportado = '"	 . $_POST['periodo'] . "'
				and version = (
					select max (version)
					from sistema.impresiones_ddjj_doiu
					where
						id_provincia = '"	 . $_SESSION['grupo'] . "'
						and periodo_reportado = '"	 . $_POST['periodo'] . "')