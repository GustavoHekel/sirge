<?php

class DdjjBackup extends PdfDdjjSirge {
	private $_db;

	public function __construct()
	{
		$this->_db = Bdd::getInstance();
	}

	public function getImpresionEnPeriodo($grupo, $periodo)
	{
		return $this->_db->fquery('getImpresionEnPeriodo', [$grupo, $periodo], false)->getCount();
	}

	public function getVersion($grupo, $periodo)
	{
		return $this->_db->fquery('getVersion', [$grupo, $periodo], false)->getCount();
	}

	public function insertarBackupDdjj($grupo, $usuario, $periodo, $fecha_backup, $nombre_backup, $version)
	{
		return $this->_db->fquery('insertarBackupDdjj', [$grupo, $usuario, $periodo, $fecha_backup, $nombre_backup, $version], false)->getError();
	}

	public function insertarMotivoReimpresionDdjj($motivo, $grupo, $periodo, $grupo, $periodo)
	{
		return $this->_db->fquery('insertarMotivoReimpresionDdjj', [$motivo, $grupo, $periodo, $grupo, $periodo], false)->getError();
	}

	public function getHistorialDdjjProvincia($id_provincia)
	{
		return $this->_db->fquery('getHistorialDdjjProvincia', [$id_provincia], false)->getResults();
	}
}