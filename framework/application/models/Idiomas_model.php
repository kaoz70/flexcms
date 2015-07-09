<?php

class Idiomas_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function get($diminutivo='es')
	{
		$this->db->where('idiomaDiminutivo', $diminutivo);
		$query = $this->db->get('idioma');
        return $query->row();
	}
	
	function getLanguages()
	{
		$this->db->cache_on();
		$query = $this->db->get('idioma');
		$this->db->cache_off();
        return $query->result_array();
	}
	
	public function getPageTranslations($paginaId)
	{
		 
		$idiomas = $this->getLanguages();

		$select = array();

		foreach ($idiomas as $key => $value) {
			$select[] = $value['idiomaDiminutivo'] . '_paginas.paginaNombreURL AS ' . $value['idiomaDiminutivo'];
		}

		$this->db->select($select);
		$this->db->from('paginas');

		foreach ($idiomas as $key => $value) {
			$this->db->join($value['idiomaDiminutivo'] . '_paginas', $value['idiomaDiminutivo'] . '_paginas.paginaId = paginas.id', 'LEFT');
		}

		$this->db->where('paginas.id', $paginaId);
		$query = $this->db->get();

		return $query->row();

	}

	/**
	 * Security check because of logs, someone was trying to access /wp/wp-admin/
	 * @param $idioma
	 * @return string
	 */
	public function check($idioma)
	{

		//Check if language exists
		$query = $this->db->where('idiomaDiminutivo', $idioma)->get('idioma');
		if(!$query->num_rows()) {

			//Load the language file for the Interface Translations
			$this->m_idioma_object = $this->get();
			$this->lang->load('ui', $this->m_idioma_object->idiomaNombre);
			$this->lang->load('errors', $this->m_idioma_object->idiomaNombre);

			show_my_404(base_url($this->uri->uri_string()), $this->m_config->theme);

		}

		return $query->row();

	}


	/*******************************************************************************************************************
	 * ------------------------------------------------- ADMIN ---------------------------------------------------------
	 ******************************************************************************************************************/

	function addLanguage()
	{
		$diminutivo = $this->input->post('idiomaDiminutivo');

		$data = array(
			'idiomaNombre' => $this->input->post('idiomaNombre'),
			'idiomaDiminutivo' => $diminutivo
		);

		$this->db->insert('idioma', $data);

		$id = $this->db->insert_id();

		/*************************************************************************
		 * Creamos las tablas donde se guardará la informacion de las traducciones
		 ************************************************************************/

		/*
		 * PAGINAS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_paginas` (
		  `'.$diminutivo.'_paginaId` int(11) NOT NULL AUTO_INCREMENT,
		  `paginaId` int(11) NOT NULL,
		  `paginaNombre` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `paginaNombreMenu` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `paginaNombreURL` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `paginaKeywords` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `paginaDescripcion` varchar(255) CHARACTER SET utf8 NOT NULL,
		  `paginaTitulo` varchar(255) CHARACTER SET utf8 NOT NULL,
		  PRIMARY KEY (`'.$diminutivo.'_paginaId`),
		  KEY `'.$diminutivo.'_paginaId` (`paginaId`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * ARTICULOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_articulos` (
		  `'.$diminutivo.'_articuloId` int(11) NOT NULL AUTO_INCREMENT,
          `articuloId` int(11) NOT NULL,
          `articuloTitulo` varchar(255) NOT NULL,
          `articuloContenido` mediumtext NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_articuloId`),
          KEY `'.$diminutivo.'_articuloId` (`articuloId`),
          CONSTRAINT `'.$diminutivo.'_articuloId` FOREIGN KEY (`articuloId`) REFERENCES `articulos` (`articuloId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * BANNER CAMPOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_banner_campos` (
		  `'.$diminutivo.'_bannerCampoId` int(11) NOT NULL AUTO_INCREMENT,
		  `bannerCampoId` int(11) NOT NULL,
		  `bannerCampoValor` text NOT NULL,
		  `bannerCampoLabel` varchar(45) DEFAULT NULL,
		  PRIMARY KEY (`'.$diminutivo.'_bannerCampoId`),
		  KEY `'.$diminutivo.'_bannerCampos` (`bannerCampoId`),
		  CONSTRAINT `'.$diminutivo.'_bannerCampos` FOREIGN KEY (`bannerCampoId`) REFERENCES `banner_campos` (`bannerCampoId`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * BANNER CAMPOS IMAGENES
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_banner_campos_rel` (
          `'.$diminutivo.'_bannerCamposRelId` int(11) NOT NULL AUTO_INCREMENT,
          `bannerCamposRelId` int(11) NOT NULL,
          `bannerCamposTexto` text,
          PRIMARY KEY (`'.$diminutivo.'_bannerCamposRelId`),
          KEY `'.$diminutivo.'_bannerCamposRelId_idx` (`bannerCamposRelId`),
          CONSTRAINT `'.$diminutivo.'_bannerCamposRelId` FOREIGN KEY (`bannerCamposRelId`) REFERENCES `banner_campos_rel` (`bannerCampoRelId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		/*
		 * CONTACTOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_contactos` (
            `'.$diminutivo.'_contactoId` int(11) NOT NULL AUTO_INCREMENT,
            `contactoId` int(11) NOT NULL,
            `contactoNombre` varchar(255) NOT NULL,
            PRIMARY KEY (`'.$diminutivo.'_contactoId`),
            KEY `'.$diminutivo.'_contactoId` (`contactoId`),
            CONSTRAINT `'.$diminutivo.'_contactoId` FOREIGN KEY (`contactoId`) REFERENCES `contactos` (`contactoId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * CONTACTO CAMPOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_contacto_campos` (
		  `'.$diminutivo.'_contactoCampoId` int(11) NOT NULL AUTO_INCREMENT,
          `contactoCampoId` int(11) NOT NULL,
          `contactoCampoValor` varchar(255) NOT NULL,
          `contactoCampoPlaceholder` varchar(255) NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_contactoCampoId`),
          KEY `'.$diminutivo.'_contactoCampoId` (`contactoCampoId`),
          CONSTRAINT `'.$diminutivo.'_contactoCampoId` FOREIGN KEY (`contactoCampoId`) REFERENCES `contacto_campos` (`contactoCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * FAQ
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_faq` (
		  `'.$diminutivo.'_faqId` int(11) NOT NULL AUTO_INCREMENT,
          `faqId` int(11) NOT NULL,
          `faqPregunta` text NOT NULL,
          `faqRespuesta` mediumtext NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_faqId`),
          KEY `'.$diminutivo.'_faqId` (`faqId`),
          CONSTRAINT `'.$diminutivo.'_faqId` FOREIGN KEY (`faqId`) REFERENCES `faq` (`faqId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * PUBLICACIONES
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_publicaciones` (
		  `'.$diminutivo.'_publicacionId` int(11) NOT NULL AUTO_INCREMENT,
          `publicacionId` int(11) NOT NULL,
          `publicacionNombre` varchar(255) NOT NULL,
          `publicacionTexto` mediumtext NOT NULL,
          `publicacionUrl` varchar(255) DEFAULT NULL,
          `publicacionLink` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_publicacionId`),
          KEY `publicacionId` (`publicacionId`),
          KEY `'.$diminutivo.'_publicacionId` (`publicacionId`),
          KEY `'.$diminutivo.'_publicacionUrl` (`publicacionUrl`),
          CONSTRAINT `'.$diminutivo.'_publicacionId` FOREIGN KEY (`publicacionId`) REFERENCES `publicaciones` (`publicacionId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * PRODUCTOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_productos` (
          `'.$diminutivo.'_productoId` int(11) NOT NULL AUTO_INCREMENT,
          `productoId` int(11) NOT NULL,
          `productoNombre` varchar(255) NOT NULL,
          `productoUrl` varchar(255) DEFAULT NULL,
          `productoKeywords` varchar(255) DEFAULT NULL,
          `productoDescripcion` varchar(255) DEFAULT NULL,
          `productoMetaTitulo` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoId`),
          KEY `'.$diminutivo.'_productoId` (`productoId`),
          KEY `'.$diminutivo.'_productoUrl` (`productoUrl`),
          CONSTRAINT `'.$diminutivo.'_productoId` FOREIGN KEY (`productoId`) REFERENCES `productos` (`productoId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO CAMPOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_producto_campos` (
		  `'.$diminutivo.'_productoCampoId` int(11) NOT NULL AUTO_INCREMENT,
		  `productoCampoId` int(11) NOT NULL,
		  `productoCampoValor` text NOT NULL,
		  PRIMARY KEY (`'.$diminutivo.'_productoCampoId`),
		  KEY `'.$diminutivo.'_productoCampoId_pc` (`productoCampoId`),
          CONSTRAINT `'.$diminutivo.'_productoCampoId_pc` FOREIGN KEY (`productoCampoId`) REFERENCES `producto_campos` (`productoCampoId`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * PRODUCTO CAMPOS REL
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_producto_campos_rel` (
          `'.$diminutivo.'_productoCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
          `productoCampoRelId` int(11) NOT NULL,
          `productoCampoRelContenido` mediumtext DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoCampoRelId`),
          KEY `'.$diminutivo.'_productoCampoRelId` (`productoCampoRelId`),
          CONSTRAINT `'.$diminutivo.'_productoCampoRelId` FOREIGN KEY (`productoCampoRelId`) REFERENCES `producto_campos_rel` (`productoCampoRelId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO CATEGORIAS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_producto_categorias` (
          `'.$diminutivo.'_productoCategoriaId` int(11) NOT NULL AUTO_INCREMENT,
          `productoCategoriaId` int(11) NOT NULL,
          `productoCategoriaNombre` varchar(255) DEFAULT NULL,
          `productoCategoriaUrl` varchar(45) DEFAULT NULL,
          `productoCategoriaDescripcion` TEXT DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoCategoriaId`),
          KEY `'.$diminutivo.'_productoCategoriaId` (`productoCategoriaId`),
          KEY `'.$diminutivo.'_productoCategoriaUrl` (`productoCategoriaUrl`),
          CONSTRAINT `'.$diminutivo.'_productoCategoriaId` FOREIGN KEY (`productoCategoriaId`) REFERENCES `producto_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO IMAGENES
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_producto_imagenes` (
          `'.$diminutivo.'_productoImagenId` int(11) NOT NULL AUTO_INCREMENT,
          `productoImagenId` int(11) NOT NULL,
          `productoImagenTexto` text NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoImagenId`),
          KEY `'.$diminutivo.'_productoImagenId` (`productoImagenId`),
          CONSTRAINT `'.$diminutivo.'_productoImagenId` FOREIGN KEY (`productoImagenId`) REFERENCES `producto_imagenes` (`productoImagenId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO VIDEOS
		 */
		$this->db->query('CREATE TABLE `'.$diminutivo.'_producto_videos` (
          `'.$diminutivo.'_productoVideoId` int(11) NOT NULL AUTO_INCREMENT,
          `productoVideoId` int(11) NOT NULL,
          `productoVideoTexto` text NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoVideoId`),
          KEY `'.$diminutivo.'_productoVideoId` (`productoVideoId`),
          CONSTRAINT `'.$diminutivo.'_productoVideoId` FOREIGN KEY (`productoVideoId`) REFERENCES `producto_videos` (`productoVideoId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO DESCARGAS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_producto_descargas` (
          `'.$diminutivo.'_productoDescargaId` int(11) NOT NULL AUTO_INCREMENT,
          `productoDescargaId` int(11) NOT NULL,
          `productoDescargaTexto` text NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoDescargaId`),
          KEY `'.$diminutivo.'_productoDescargaId` (`productoDescargaId`),
          CONSTRAINT `'.$diminutivo.'_productoDescargaId` FOREIGN KEY (`productoDescargaId`) REFERENCES `producto_descargas` (`productoDescargaId`) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * PRODUCTO LISTADO PREDEFINIDO
		 */
		$this->db->query('CREATE TABLE `'.$diminutivo.'_producto_campos_listado_predefinido` (
          `'.$diminutivo.'_productoCamposListadoPredefinidoId` int(11) NOT NULL AUTO_INCREMENT,
          `productoCamposListadoPredefinidoId` int(11) DEFAULT NULL,
          `productoCamposListadoPredefinidoTexto` varchar(45) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_productoCamposListadoPredefinidoId`),
          KEY `'.$diminutivo.'_productoCamposListadoPredefinidoId_pclpr_idx` (`productoCamposListadoPredefinidoId`),
          CONSTRAINT `'.$diminutivo.'_productoCamposListadoPredefinidoId_pclpr` FOREIGN KEY (`productoCamposListadoPredefinidoId`) REFERENCES `producto_campos_listado_predefinido` (`productoCamposListadoPredefinidoId`) ON DELETE CASCADE ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * SERVICIOS
		 */
		$this->db->query('CREATE  TABLE `'.$diminutivo.'_servicios` (
          `'.$diminutivo.'_servicioId` int(11) NOT NULL AUTO_INCREMENT,
          `servicioId` int(11) DEFAULT NULL,
          `servicioTitulo` varchar(45) DEFAULT NULL,
          `servicioTexto` mediumtext,
          `servicioUrl` varchar(45) DEFAULT NULL,
          `servicioKeywords` varchar(255) DEFAULT NULL,
          `servicioDescripcion` varchar(255) DEFAULT NULL,
          `servicioMetaTitulo` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_servicioId`),
          KEY `servicioId_idx_idx` (`servicioId`),
          KEY `'.$diminutivo.'_servicioId` (`servicioId`),
          KEY `'.$diminutivo.'_servicioUrl` (`servicioUrl`),
          CONSTRAINT `'.$diminutivo.'_servicioId` FOREIGN KEY (`servicioId`) REFERENCES `servicios` (`servicioId`) ON DELETE CASCADE ON UPDATE NO ACTION
        ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8');

		/*
		 * MODULOS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_modulos` (
		  `'.$diminutivo.'_moduloId` int(11) NOT NULL AUTO_INCREMENT,
		  `moduloId` int(11) NOT NULL,
		  `moduloNombre` varchar(255) DEFAULT NULL,
		  `moduloHtml` text,
		  PRIMARY KEY (`'.$diminutivo.'_moduloId`),
		  KEY `moduloId` (`moduloId`),
		  KEY `'.$diminutivo.'_moduloId_m` (`moduloId`),
          CONSTRAINT `'.$diminutivo.'_moduloId_m` FOREIGN KEY (`moduloId`) REFERENCES `modulos` (`moduloId`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		//Creamos los nuevos modulos dentro de cada idioma
		$modulos = $this->db
			->select('moduloId')
			->get('modulos');

		$this->db->insert_batch($diminutivo.'_modulos', $modulos->result_array());

		/*
		 * DESCARGAS CATEGORIAS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_descargas_categorias` (
		  `'.$diminutivo.'_descargaCategoriaId` int(11) NOT NULL AUTO_INCREMENT,
          `descargaCategoriaId` int(11) NOT NULL,
          `descargaCategoriaNombre` varchar(255) DEFAULT NULL,
          `descargaCategoriaUrl` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_descargaCategoriaId`),
          KEY `'.$diminutivo.'_descargaCategoriaId_fk` (`descargaCategoriaId`),
          KEY `'.$diminutivo.'_descargaCategoriaUrl` (`descargaCategoriaUrl`),
          CONSTRAINT `'.$diminutivo.'_descargaCategoriaId_fk` FOREIGN KEY (`descargaCategoriaId`) REFERENCES `descargas_categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * DESCARGAS
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_descargas` (
		  `'.$diminutivo.'_descargaId` int(11) NOT NULL AUTO_INCREMENT,
          `descargaId` int(11) NOT NULL,
          `descargaNombre` varchar(255) NOT NULL,
          `descargaUrl` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_descargaId`),
          KEY `'.$diminutivo.'_descargaId` (`descargaId`),
          KEY `'.$diminutivo.'_descargaUrl` (`descargaUrl`),
          CONSTRAINT `'.$diminutivo.'_descargaId` FOREIGN KEY (`descargaId`) REFERENCES `descargas` (`descargaId`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * ENLACES
		 */
		$this->db->query('CREATE TABLE IF NOT EXISTS `'.$diminutivo.'_enlaces` (
		  `'.$diminutivo.'_enlaceId` int(11) NOT NULL AUTO_INCREMENT,
          `enlaceId` int(11) NOT NULL,
          `enlaceTexto` varchar(255) NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_enlaceId`),
          KEY `'.$diminutivo.'_enlaceId` (`enlaceId`),
          CONSTRAINT `'.$diminutivo.'_enlaceId` FOREIGN KEY (`enlaceId`) REFERENCES `enlaces` (`enlaceId`) ON DELETE CASCADE ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1');

		/*
		 * USUARIOS CAMPOS
		 */
		$this->db->query('CREATE  TABLE `'.$diminutivo.'_user_fields` (
          `'.$diminutivo.'_userFieldId` INT NOT NULL AUTO_INCREMENT ,
          `userFieldContent` TEXT NULL ,
          `userFieldLabel` VARCHAR(45) NULL ,
          `userFieldId` INT NULL ,
          `userFieldPlaceholder` varchar(150) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_userFieldId`) ,
          INDEX `userFieldId_'.$diminutivo.'_uf_idx` (`userFieldId` ASC) ,
          CONSTRAINT `userFieldId_'.$diminutivo.'_uf`
            FOREIGN KEY (`userFieldId` )
            REFERENCES `user_fields` (`userFieldId` )
            ON DELETE CASCADE
            ON UPDATE CASCADE)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8
        COLLATE = utf8_general_ci
        AUTO_INCREMENT=1');

		/*
		 * MAPA CAMPOS
		 */
		$this->db->query('CREATE TABLE `'.$diminutivo.'_mapas_campos` (
          `'.$diminutivo.'_mapaCampoId` int(11) NOT NULL AUTO_INCREMENT,
          `mapaCampoLabel` varchar(45) DEFAULT NULL,
          `mapaCampoId` int(11) NOT NULL,
          PRIMARY KEY (`'.$diminutivo.'_mapaCampoId`),
          KEY `'.$diminutivo.'_mapaCampoId_mc_idx` (`mapaCampoId`),
          CONSTRAINT `'.$diminutivo.'_mapaCampoId_mc` FOREIGN KEY (`mapaCampoId`) REFERENCES `mapas_campos` (`mapaCampoId`) ON DELETE CASCADE ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		/*
		 * MAPA CAMPOS REL
		 */
		$this->db->query('CREATE TABLE `'.$diminutivo.'_mapa_campo_rel` (
          `'.$diminutivo.'_mapaCampoRelId` int(11) NOT NULL AUTO_INCREMENT,
          `mapaCampoRelId` int(11) DEFAULT NULL,
          `mapaCampoTexto` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`'.$diminutivo.'_mapaCampoRelId`),
          KEY `'.$diminutivo.'_mapaCampoRel_mcr_idx` (`mapaCampoRelId`),
          CONSTRAINT `'.$diminutivo.'_mapaCampoRel_mcr` FOREIGN KEY (`mapaCampoRelId`) REFERENCES `mapa_campo_rel` (`mapaCampoRelId`) ON DELETE CASCADE ON UPDATE NO ACTION
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8');

		/*
		 * DIRECCIONES
		 */
		$this->db->query('CREATE  TABLE `'.$diminutivo.'_contacto_direcciones` (
          `'.$diminutivo.'_contactoDireccionId` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
          `contactoDireccionId` INT UNSIGNED NULL ,
          `contactoDireccion` MEDIUMTEXT NULL ,
          PRIMARY KEY (`'.$diminutivo.'_contactoDireccionId`) ,
          INDEX `'.$diminutivo.'_contactoDireccion_idx_idx` (`contactoDireccionId` ASC) ,
          CONSTRAINT `'.$diminutivo.'_contactoDireccion_idx`
            FOREIGN KEY (`contactoDireccionId` )
            REFERENCES `contacto_direcciones` (`contactoDireccionId` )
            ON DELETE CASCADE
            ON UPDATE CASCADE)');

		return $id;

	}


	function deleteLanguage($id)
	{
		$idioma = $this->getLanguageInfo($id);
		$diminutivo = $idioma->idiomaDiminutivo;

		$this->db->where('idiomaId', $id);
		$this->db->delete('idioma');

		/************************************************************************
		 * Eliminamos las tablas donde se guarda la informacion de cada idioma
		 ***********************************************************************/

		$this->db->query('DROP TABLE '.$diminutivo.'_paginas,
		 '.$diminutivo.'_articulos,
		 '.$diminutivo.'_banner_campos,
		 '.$diminutivo.'_banner_campos_rel,
		 '.$diminutivo.'_contactos,
		 '.$diminutivo.'_contacto_campos,
		 '.$diminutivo.'_contacto_direcciones,
		 '.$diminutivo.'_faq,
		 '.$diminutivo.'_publicaciones,
		 '.$diminutivo.'_productos,
		 '.$diminutivo.'_producto_campos,
		 '.$diminutivo.'_producto_campos_rel,
		 '.$diminutivo.'_producto_categorias,
		 '.$diminutivo.'_modulos,
		 '.$diminutivo.'_producto_imagenes,
		 '.$diminutivo.'_producto_videos,
		 '.$diminutivo.'_producto_descargas,
		 '.$diminutivo.'_producto_campos_listado_predefinido,
		 '.$diminutivo.'_descargas_categorias,
		 '.$diminutivo.'_descargas,
		 '.$diminutivo.'_servicios,
		 '.$diminutivo.'_user_fields,
		 '.$diminutivo.'_mapas_campos,
		 '.$diminutivo.'_mapa_campo_rel,
		 '.$diminutivo.'_enlaces'
		);
	}

	function getLanguageInfo($id)
	{
		$this->db->where('idiomaId', $id);
		$query = $this->db->get('idioma');

		return $query->row();
	}

	function updateLanguage()
	{

		$diminutivoAnterior = $this->input->post('idiomaDiminutivoAnterior');
		$diminutivo = $this->input->post('idiomaDiminutivo');

		$data = array(
			'idiomaNombre' => $this->input->post('idiomaNombre'),
			'idiomaDiminutivo' => $diminutivo
		);

		$id = $this->uri->segment(4);

		$this->db->where('idiomaId', $id);
		$this->db->update('idioma', $data);

		/************************************************************************
		 * Modificamos las tablas donde se guarda la informacion de cada idioma
		 ***********************************************************************/

		if($diminutivoAnterior != $diminutivo)
		{

			/*
			 * ARTICULOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_articulos` TO `'.$diminutivo.'_articulos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_articulos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_articuloId` `'.$diminutivo.'_articuloId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PAGINAS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_paginas` TO `'.$diminutivo.'_paginas`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_paginas`
			CHANGE COLUMN `'.$diminutivoAnterior.'_paginaId` `'.$diminutivo.'_paginaId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * BANNER CAMPOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_banner_campos` TO `'.$diminutivo.'_banner_campos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_banner_campos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_bannerCampoId` `'.$diminutivo.'_bannerCampoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * BANNER CAMPOS IMAGENES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_banner_campos_rel` TO `'.$diminutivo.'_banner_campos_rel`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_banner_campos_rel`
			CHANGE COLUMN `'.$diminutivoAnterior.'_bannerCamposRelId` `'.$diminutivo.'_bannerCamposRelId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * CONTACTOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_contactos` TO `'.$diminutivo.'_contactos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_contactos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_contactoId` `'.$diminutivo.'_contactoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');


			/*
			 * CONTACTO CAMPOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_contacto_campos` TO `'.$diminutivo.'_contacto_campos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_contacto_campos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_contactoCampoId` `'.$diminutivo.'_contactoCampoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * FAQ
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_faq` TO `'.$diminutivo.'_faq`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_faq`
			CHANGE COLUMN `'.$diminutivoAnterior.'_faqId` `'.$diminutivo.'_faqId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PUBLICACIONES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_publicaciones` TO `'.$diminutivo.'_publicaciones`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_publicaciones`
			CHANGE COLUMN `'.$diminutivoAnterior.'_publicacionId` `'.$diminutivo.'_publicacionId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_productos` TO `'.$diminutivo.'_productos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_productos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoId` `'.$diminutivo.'_productoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO CAMPOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_campos` TO `'.$diminutivo.'_producto_campos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_campos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoCampoId` `'.$diminutivo.'_productoCampoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO CAMPOS RELACIONES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_campos_rel` TO `'.$diminutivo.'_producto_campos_rel`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_campos_rel`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoCampoRelId` `'.$diminutivo.'_productoCampoRelId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO CATEGORIAS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_categorias` TO `'.$diminutivo.'_producto_categorias`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_categorias`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoCategoriaId` `'.$diminutivo.'_productoCategoriaId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO IMAGENES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_imagenes` TO `'.$diminutivo.'_producto_imagenes`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_imagenes`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoImagenId` `'.$diminutivo.'_productoImagenId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO IMAGENES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_videos` TO `'.$diminutivo.'_producto_videos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_videos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoVideoId` `'.$diminutivo.'_productoVideoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO DESCARGAS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_descargas` TO `'.$diminutivo.'_producto_descargas`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_descargas`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoDescargaId` `'.$diminutivo.'_productoDescargaId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * PRODUCTO LISTADO PREDEFINIDO
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_producto_campos_listado_predefinido` TO `'.$diminutivo.'_producto_campos_listado_predefinido`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_producto_campos_listado_predefinido`
			CHANGE COLUMN `'.$diminutivoAnterior.'_productoCamposListadoPredefinidoId` `'.$diminutivo.'_productoCamposListadoPredefinidoId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * SERVICIOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_servicios` TO `'.$diminutivo.'_servicios`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_servicios`
			CHANGE COLUMN `'.$diminutivoAnterior.'_servicioId` `'.$diminutivo.'_servicioId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * MODULOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_modulos` TO `'.$diminutivo.'_modulos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_modulos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_moduloId` `'.$diminutivo.'_moduloId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * DESCARGAS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_descargas` TO `'.$diminutivo.'_descargas`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_descargas`
			CHANGE COLUMN `'.$diminutivoAnterior.'_descargaId` `'.$diminutivo.'_descargaId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * DESCARGA CATEGORIAS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_descargas_categorias` TO `'.$diminutivo.'_descargas_categorias`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_descargas_categorias`
			CHANGE COLUMN `'.$diminutivoAnterior.'_descargaCategoriaId` `'.$diminutivo.'_descargaCategoriaId`  int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * ENLACES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_enlaces` TO `'.$diminutivo.'_enlaces`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_enlaces`
			CHANGE COLUMN `'.$diminutivoAnterior.'_enlaceId` `'.$diminutivo.'_enlaceId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * USUARIOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_user_fields` TO `'.$diminutivo.'_user_fields`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_user_fields`
			CHANGE COLUMN `'.$diminutivoAnterior.'_userFieldId` `'.$diminutivo.'_userFieldId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * MAPAS CAMPOS
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_mapas_campos` TO `'.$diminutivo.'_mapas_campos`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_mapas_campos`
			CHANGE COLUMN `'.$diminutivoAnterior.'_mapaCampoId` `'.$diminutivo.'_mapaCampoId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * MAPAS CAMPOS REL
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_mapa_campo_rel` TO `'.$diminutivo.'_mapa_campo_rel`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_mapa_campo_rel`
			CHANGE COLUMN `'.$diminutivoAnterior.'_mapaCampoRelId` `'.$diminutivo.'_mapaCampoRelId` int(11) NOT NULL AUTO_INCREMENT FIRST');

			/*
			 * DIRECCIONES
			 */
			$this->db->query('RENAME TABLE `'.$diminutivoAnterior.'_contacto_direcciones` TO `'.$diminutivo.'_contacto_direcciones`');
			$this->db->query('ALTER TABLE `'.$diminutivo.'_contacto_direcciones`
			CHANGE COLUMN `'.$diminutivoAnterior.'_contactoDireccionId` `'.$diminutivo.'_contactoDireccionId` int(11) NOT NULL AUTO_INCREMENT FIRST');

		}

	}

}