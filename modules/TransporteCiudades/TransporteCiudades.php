<?php
/**
 * 	Clase encargada de la administración de médicos y especialidades médicas.
 */

if (!defined('_PS_VERSION_'))
	exit;

class TransporteCiudades extends Module
{

    //private $_html = '';
    //private $_postErrors = array();
    //private $_msg='';
    private $select_limit = 100;
    private $offset = null;
    private $pag = null;
    private $total__rows = 0;
    private $output = '';
    private $transciuMsg = '';
    private $perfil_usuario = '';
    private $json_empleado = '';
    private $paisdefault = 69;
    /**
     * [$val_random para asignar el id del visitador si es este el del perfil usado]
     * @var integer
     */
    private $val_random = 0;


    private $error = 0;
    private $errores = '';
    private $lista_campos_input1 = array();
    private $bd_fielsd_save = array();
    //private $bd_data_save = array();

    /**
     * [$permisos_basicos 0 = superadmin, 2 = supervisor visitadores, 3 = visitador medico]
     * @var integer
     */
    private $permisos_basicos = 3;

    function __construct() {  //informacion del modulo
		$this->name = 'TransporteCiudades';
		$this->tab = 'administration';
		$this->version = '1 Alpha';
		$this->author = 'Farmalisto - Ewing Vásquez';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Administración de costos de transporte y ciudades');
		$this->description = $this->l('Actualiza las características de transporte y ciudades de Farmalisto SAS');

		$nombs[]="plane.png";
		$nombs[]="logo.png";
		//$nombs[]="businesspeople_med.jpg";			

		$rand_keys = array_rand($nombs, 1);

        $this->context->smarty->assign('base_dir', __PS_BASE_URI__);
        $this->context->smarty->assign('transciuLogo', '<img src="' . $this->_path ."".$nombs[$rand_keys].'" width="64px" height="64px" alt="transporte" title="transporte" />');
        $this->context->smarty->assign('transciuPath', $this->_path);
        $this->context->smarty->assign('pathModule', dirname(__FILE__));
        $this->context->smarty->assign('transciuMsg', $this->transciuMsg);
        $this->context->smarty->assign('tokenn', md5(pSQL(_COOKIE_KEY_.'AdminCartRules'.(int)Tab::getIdFromClassName('AdminCartRules').(int)$this->context->employee->id)) );
        $this->context->smarty->assign('empid',(int)$this->context->employee->id );
        $this->paisdefault = Configuration::get('PS_COUNTRY_DEFAULT');
        $this->context->smarty->assign('expressEnabled',Configuration::get('ENVIO_EXPRESS'));
        $this->context->smarty->assign('Umbral',Configuration::get('PS_SHIPPING_FREE_PRICE'));
    }

    public function install() // parametros de instalación del modulo
	{
		if (!$id_tab = Tab::getIdFromClassName('TransporteCiudadesMenu'))  // para crear acceso en menu back office / clase creada
		{
		$tab = new Tab();
		$tab->class_name = 'TransporteCiudadesMenu';		//la clase que redirecciona el link del menu a la configuracion
		$tab->module = 'TransporteCiudades';	// nombre del modulo creado
		$tab->id_parent = (int)Tab::getIdFromClassName('AdminParentShipping'); //aparecerá al final del menú transportadores
		foreach (Language::getLanguages(false) as $lang)
			$tab->name[(int)$lang['id_lang']] = 'Precios transporte y ciudades'; // texto a mostrar en el menu
		if (!$tab->save())
			return $this->_abortInstall($this->l('Imposible crear la pestaña de nuevo'));
		}

		$this->_clearCache('TransporteCiudades.tpl');
		
		if (!parent::install()) {	
			return false;
		}
		$this->createTables();
		return true;
	}
	
	protected function createTables() {

		/* tabla temporal cargue */
		$res = (bool)Db::getInstance()->execute("
			CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."tmp_precios_transportador` (
		 		`cod_postal` varchar(255) NOT NULL,
				`id_transportador` int(11) NOT NULL,
				`precio` int(11) NOT NULL,
				`flag` enum('i','u','n') NOT NULL DEFAULT 'n' COMMENT 'insert, update, no action',
				`id_estado` int(11) DEFAULT NULL,
				`id_ciudad` int(11) DEFAULT NULL,
				`id_colonia` int(11) DEFAULT NULL
			) ENGINE=Aria DEFAULT CHARSET=utf8");

	}

	public function uninstall()  // desinstalacion
	{
		$this->_clearCache('TransporteCiudades.tpl');
		return parent::uninstall();
	}


	public function validarCodigop() {

		/* si existe el transportador y el codigo postal */
		"UPDATE `"._DB_PREFIX_."tmp_precios_transportador` pt 
		INNER JOIN  
			(SELECT codp.codigo_postal FROM `"._DB_PREFIX_."cod_postal` codp 
				INNER JOIN `"._DB_PREFIX_."cities_col` ciu 
					ON ( ciu.id_city = codp.id_ciudad AND ciu.id_country = ".$this->paisdefault.") 
				GROUP BY codp.codigo_postal
			) cp 
		ON ( pt.cod_postal = cp.codigo_postal )
		INNER JOIN `"._DB_PREFIX_."carrier` car ON 
			(car.id_reference = pt.id_transportador AND car.deleted = 0 AND car.active=1) 
		SET pt.flag = 'i' ";

		/* si el transportador y codigo postal ya se encuentran registrados */
		"UPDATE `"._DB_PREFIX_."tmp_precios_transportador` pt 
		INNER JOIN `"._DB_PREFIX_."precio_tr_codpos` ptc ON 
			(pt.cod_postal = ptc.codigo_postal AND pt.id_transportador = ptc.id_carrier) 
			SET pt.flag = 'u' ";

		/* si el precio de envio no existe o es inferior a 0 */
		"UPDATE `"._DB_PREFIX_."tmp_precios_transportador` pt 
			SET pt.flag = 'n' 
			WHERE pt.precio < 0 OR 
      		pt.precio IS NULL ";
	}

	public function actualizarCodigop() {

		"UPDATE `"._DB_PREFIX_."precio_tr_codpos` ptc 
		INNER JOIN `"._DB_PREFIX_."tmp_precios_transportador` pt 
		ON ( pt.flag = 'u' AND pt.cod_postal = cp.codigo_postal AND pt.id_transportador = ptc.id_carrier )		
		SET ptc.precio = pt.precio ";

	}

	public function insertarCodigop() {

		"INSERT INTO `"._DB_PREFIX_."precio_tr_codpos` (codigo_postal, id_carrier, precio)
		SELECT cod_postal, id_transportador, precio FROM `"._DB_PREFIX_."tmp_precios_transportador` 
		WHERE flag = 'i'";
	}

	public function reporteCodigopMalo() {
		"SELECT * FROM `"._DB_PREFIX_."tmp_precios_transportador` WHERE flag = 'n' ";
	}


    // control de flujo del modulo      
    public function getContent() {
    	if ($this->json_empleado == '') {
    		$this->json_empleado = ' "Empleado": {"id_employee" : "'.$this->context->employee->id.'", "nombres" : "'.$this->context->employee->firstname.'", "apellidos" : "'.$this->context->employee->lastname.'"}';
    	}

    	$this->listadoEstadoDepto();
    	// $this->listaVisitadorMedico();
    	// $this->listaVisitadorMedicoFull();
    	
        if (Tools::getValue('opc_ini')) {

			$primeraopc = Tools::getValue('opc_ini');

		switch ($primeraopc) {
			case 'cargatranscp':
				if($guardar == 1 && $validado == 1) {
					if ( $retorno = $this->registernewmed() ) { //REGISTRAR EL MEDICO EN LA BD	
						$this->output .= $this->displayConfirmation($this->l('El cupón médico se registró correctamente.'));	    									
					} else {
						$this->output .= $this->adminDisplayWarning($this->l('El cupón médico NO se registró correctamente.'.$this->errores));
					}
					return $this->output . $this->displayForm();
				} else {

					return $this->output . $this->displayCreaCupon();
				}
				break;

			case 'actumed': //$this->output.= "<br> Modificar cupón médico";

				switch (Tools::getValue('step_opc')) {
	    			case 1 :
						if ( Tools::getValue('doc_fnd') != '' ) {
	    					$this->cargaMedicoPorIdCupon( Tools::getValue('doc_fnd') );
		    				return $this->output . $this->displayModiCupon();
						} else {
		    				$this->output .= $this->adminDisplayWarning($this->l('No se enviaron todos los datos necesarios, Seleccione un médico a modificar.'.$this->errores));
		    				return $this->output . $this->displayForm();
		    			}

	    				break;
	    			case 2 : 
	    				if ( $validado ) {
		    				if ($retorno = $this->registeroldmed()) {
		    					$this->output .= $this->displayConfirmation($this->l('El cupón médico se modificó correctamente.'));
		    				} else {
		    					$this->output .= $this->adminDisplayWarning($this->l('Ocurrio un error al modificar el cupón médico.'.$this->errores));
		    				}
		    				return $this->output . $this->displayForm();
		    			} else {
		    				$this->output .= $this->adminDisplayWarning($this->l('No se enviaron todos los datos necesarios.'.$this->errores));
		    				return $this->output . $this->displayModiCupon();
		    			}
	    				break;
	    		}

				return $this->output . $this->displayModiCupon();
				break;


			default:	$this->output.= "<div style='width:100%; float: left;'> <br> Opción no disponible </div>";

			break;
		}

	    	return $this->output . $this->displayForm();
		} elseif(Tools::isSubmit('submitTransporteCiudades')) {
			$this->output .= $this->displayConfirmation($this->l('No seleccionó ninguna opción.'));
			return $this->output . $this->displayForm();
        } else {
            return $this->displayForm();
        }
    }

	public function remomeCharSql($string, $length = NULL){
		$string = trim($string);
	        
	        $array=array("\"","$","&","'","(",")","*","+",",","-","/",":",";","<","=",">","?","[","]","^","`","{","|","}","~");
		$string = utf8_decode($string);
		$string = htmlentities($string, ENT_NOQUOTES| ENT_IGNORE, "UTF-8");
		$string = str_replace($array, "", $string);        
	        $string = preg_replace( "/([ ]+)/", " ", $string );
		
		$length = intval($length);
		if ($length > 0){
			$string = substr($string, 0, $length);
		}
		return $string;
	}



    public function listadoEstadoDepto() {

    	$sql = 'SELECT id_state, name FROM ' . _DB_PREFIX_ . 'state WHERE id_country = '.$this->paisdefault.' ORDER BY name ASC';
        if ($results = Db::getInstance()->ExecuteS($sql)) {

        	$EstadoDepto_lista[''] = ' -- Seleccione -- ';
            
            foreach ($results as $valores) {

               $EstadoDepto_lista[$valores['id_state']]=$valores['name'];
            }
        }

        $this->context->smarty->assign(array('EstadoDepto'=> $EstadoDepto_lista));
        
    }



// muestra el formulario principal del modulo
    public function displayForm() {
    	$this->listadoEstadoDepto();
    	// $this->listaVisitadorMedico();
    	// $this->listaVisitadorMedicoFull();
        return $this->display(__FILE__, 'tpl/formulario.tpl');
    }



       
}

?>
