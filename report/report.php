<?php
class Ventas {
  public $meta;
  private $metaDia;
  private $hoy;
  private $mes = array();
  private $dia = array();
  private $porcentaje;
  private $resultadoscalificaciones;
  private $arraycalificaciondiaria;
  private $porcentajecalificacionmes;

  public function __construct()
  {
  	$config = $this->readConfig();
    $this->meta=$config["meta"];
    $this->metaDia=$this->meta/24;
    $this->hoy = getdate();
    $this->porcentaje = 0;
    $this->conectar();
  }
/*-----------------------------Funcion-de-Conexion-------------------------------------------*/
  private function conectar()
  {
  	$i=0;
      $con = new mysqli("col1-repo.cuznbgafgkfl.us-east-1.rds.amazonaws.com:3808","farmalisto","F4rm4l1st02015**","farmalisto_colombia", "3808");
   if ($con->connect_errno) {
     printf("Conexión fallida: %s\n", $con->connect_errno);
     return false;
     exit();
 	}
  	$result = $con->query("SELECT COUNT(DISTINCT po.id_order)as ordenes ,
  						   sum(DISTINCT po.total_paid) as Total,
  						   SUBSTRING(invoice_date,1,4),
						   SUBSTRING(invoice_date,6,2),
  						   SUBSTRING(invoice_date,9,2),
						   SUBSTRING(invoice_date,11,9),
  						   po.invoice_date
		FROM ps_orders po INNER JOIN ps_order_detail pod on pod.id_order = po.id_order
		where SUBSTRING(invoice_date,6,2) = ". $this->hoy['mon'] ."
		and SUBSTRING(invoice_date,1,4) = ".$this->hoy['year']."
		-- and invoice_date >= DATE(DATE_SUB(NOW(), INTERVAL 7 DAY))
		and po.current_state IN (2,3,4,5,9,12,20,22)
		group by SUBSTRING(invoice_date,1,4),
		SUBSTRING(invoice_date,6,2),
		SUBSTRING(invoice_date,9,2) ORDER BY invoice_date ASC");
  	while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
  		$this->mes['valor'][$i] = +$row['Total'];
  		$this->mes['cantidad'][$i] = $row['ordenes'];
  		$this->mes['fecha'][$i] = $row['SUBSTRING(invoice_date,1,4)']
  								   ."/".$row['SUBSTRING(invoice_date,6,2)']
  								   ."/".$row['SUBSTRING(invoice_date,9,2)'];
  		$i=$i+1;
  	}
  	$i=0;
  	$result = $con->query("SELECT COUNT(DISTINCT po.id_order)as ordenes,
  						   sum(DISTINCT po.total_paid) as Total,
  						   sum(pod.product_quantity) as cantidad,
  						   SUBSTRING(invoice_date,1,4),
  						   SUBSTRING(invoice_date,6,2),
  						   SUBSTRING(invoice_date,9,2),
						   SUBSTRING(invoice_date,12,2),po.invoice_date
		FROM ps_orders po INNER JOIN ps_order_detail pod on pod.id_order = po.id_order
  			where SUBSTRING(invoice_date,9,2) = ".$this->hoy['mday']." and
			SUBSTRING(invoice_date,6,2) = ". $this->hoy['mon'] ."
  			and SUBSTRING(invoice_date,1,4) = ".$this->hoy['year']."
  			and po.current_state IN (2,3,4,5,9,12,20,22) group by SUBSTRING(invoice_date,12,2) ORDER BY invoice_date ASC");
  	//if(!( mysql_fetch_array($result, MYSQL_ASSOC)))
  	/*{
  		$this->dia['cantidad'][$i]=1;
  		$this->dia['valor'][$i]=1;
  		$this->dia['fecha'][$i]=1;
  	}*/
  	while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
  		$this->dia['valor'][$i] = +$row['Total'];
  		$this->dia['cantidad'][$i] = $row['ordenes'];
  		$this->dia['fecha'][$i] = $row['SUBSTRING(invoice_date,12,2)'];
  		$i=$i+1;
  	}

    $result = $con->query("
            SELECT
              COUNT(*) as cant,
              'roj' as star,
              DATE_FORMAT(date_qualification,'%h %p') AS hora,
              DATE_FORMAT(date_qualification,'%H') AS hora2
            FROM ps_quality_score
            WHERE qualification <= 3
             AND date_qualification >= DATE_FORMAT( NOW() ,'%Y-%m-%d 00:00:00')
            GROUP BY DATE_FORMAT(date_qualification,'%H') 
            UNION
            SELECT
              COUNT(*) as cant,
              'dor' as star,
              DATE_FORMAT(date_qualification,'%h %p') AS hora,
              DATE_FORMAT(date_qualification,'%H') AS hora2
            FROM ps_quality_score
            WHERE qualification > 3
             AND date_qualification >= DATE_FORMAT( NOW() ,'%Y-%m-%d 00:00:00')
            GROUP BY DATE_FORMAT(date_qualification,'%H') 
            ORDER BY hora2");

    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

      if ( !isset( $this->resultadoscalificaciones[$row['hora']]['dor'] )  && !isset( $this->resultadoscalificaciones[$row['hora']]['dor'] ) ) {
        $this->resultadoscalificaciones[ $row['hora'] ] ['dor'] = 0;
        $this->resultadoscalificaciones[ $row['hora'] ] ['roj'] = 0;
      }

      if ( $row['star'] == 'roj' ) {
        $this->resultadoscalificaciones[ $row['hora'] ] ['roj'] += $row['cant'];
        $this->resultadoscalificaciones[ $row['hora'] ] ['dor'] += 0;
      } 

      if ( $row['star'] == 'dor' ) {
        $this->resultadoscalificaciones[ $row['hora'] ] ['dor'] += $row['cant'];
        $this->resultadoscalificaciones[ $row['hora'] ] ['roj'] += 0;
      }

    }

    $result = $con->query("
      SELECT
        DATE_FORMAT(date_qualification, '%Y/%m/%d') date_qualification,
        GROUP_CONCAT( qualification ) qualification
      FROM ps_quality_score 
      GROUP BY DATE_FORMAT(date_qualification, '%Y/%m/%d')
      ORDER BY date_qualification DESC
      LIMIT 6");
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

      $cantidad45 = 0;
      $cantidad13 = 0;
      $qualifications = explode(',', $row['qualification']);

      foreach ($qualifications as $key => $value) {
        ($value >= 4) ? ($cantidad45 += 1) : ($cantidad13 += 1);
      }

      $porcentaje13 = round( ( $cantidad13 / count($qualifications) ) * 100);
      $porcentaje45 = round( ( $cantidad45 / count($qualifications) ) * 100);

      ($porcentaje13 >= $porcentaje45) ? ($this->arraycalificaciondiaria[$row['date_qualification']]['roj'] = $porcentaje13) : ($this->arraycalificaciondiaria[$row['date_qualification']]['dor'] = $porcentaje45);
    }

    $this->arraycalificaciondiaria = array_reverse($this->arraycalificaciondiaria);

    $result = $con->query("
      SELECT GROUP_CONCAT( qualification ) qualification
      FROM ps_quality_score
      WHERE date_qualification >= DATE_FORMAT( NOW() , '%Y-%m-01' )");
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {

      $cantidad45 = 0;
      $cantidad13 = 0;
      $porcentajemesql = explode(',', $row['qualification']);

      foreach ($porcentajemesql as $key => $value) {
        ($value >= 4) ? ($cantidad45 += 1) : ($cantidad13 += 1);
      }

      $porcentaje13 = round( ( $cantidad13 / count($porcentajemesql) ) * 100);
      $porcentaje45 = round( ( $cantidad45 / count($porcentajemesql) ) * 100);

      ($porcentaje13 >= $porcentaje45) ? ($this->porcentajecalificacionmes['roj'] = $porcentaje13) : ($this->porcentajecalificacionmes['dor'] = $porcentaje45);
    }
  }
/*-----------------------------Funciones-de-Graficas-----------------------------------------*/
  private function ejeHorizontal($array)
  {
  	if(count($array['cantidad'])>0)
  	{$a=100/(count($array['cantidad']));}
  	else
  	{$a=10;}
  	return $a;
  }
  private function ejeVertical($array)
  {
	$b=ceil(max($array['valor'])/1000);
	$c=ceil(max($array['cantidad'])/5)*5;
	return array($b, $c);
  }
  private function graficar($array)
  {
  	$ancho = $this->ejeHorizontal($array);
  	$max = $this->ejeVertical($array);
  	$maxValor = $max[0];
  	$maxCantidad = $max[1];
  	
  	for ($i=0;$i<count($array['cantidad']); $i++ )
  	{
  		$this->division($ancho);
  		/*-----------------Grafico de barras------------------------*/
  		$alto=($array['valor'][$i]*100/$maxValor)/1000;
  		$valor = round($array['valor'][$i]/1000, 0);
  		if($valor>999)
  		{$valor=substr($valor, 0, 1) . "'" . substr($valor, 1);}
		$this->barras($alto, $valor);
  		/*-----------------Grafico de Puntos-------------------------*/
  		$alto=$array['cantidad'][$i]*100/$maxCantidad;
  		if($i>0)
  		{
  			$acum=($array['cantidad'][$i-1]*100/$maxCantidad);
  			$x0=49;
  			$x1=149;
  			$y0=$acum;
  			$y1=$alto;
  			$m=-($y1-$y0)/($x1-$x0);
  			for($x=$x0;$x<$x1;$x=$x+5)
  			{
  				$y=$m*($x-$x0)+$alto;
  				$this->lineas($x, $y);
  			}
  		}
  		else{$acum=0;}
  		$this->puntos($alto, $array['cantidad'][$i]);
  	}
  	$this->finGraficas();
  	
  	/*-----------------etiquetas de la tabla-------------------------*/
  	for ($i=0;$i<count($array['cantidad']); $i++ )
  	{
  		$dia=explode("/",$array['fecha'][$i]);
  		if(isset($dia[2])){$this->escala($ancho, ($dia[2])*1);}
  		else{$this->escala($ancho, $array['fecha'][$i]);}
  		unset ($dia);
  	}
  }
  /*-----------------------------Funciones-de-Tablas-----------------------------------------*/
  private function tabularMes($array)
  {
  	if(count($array['cantidad'])<8){
  		for ($i=0;$i<count($array['cantidad']); $i++ )
  		{
			$this->fila($i, $array['fecha'][$i], $array['cantidad'][$i], $array['valor'][$i]);
  		}
  	}
  	else{
  		for ($i=0;$i<8; $i++ )
  		{
  			$j=count($array['cantidad'])-8;
  			$this->fila($i, $array['fecha'][$i+$j], $array['cantidad'][$i+$j], $array['valor'][$i+$j]);
  		}
  	}
  }
  private function tabularDia($array)
  {
  	$hora='';
  	for ($i=0;$i<count($array['cantidad']); $i++ )
  	{
  		if ($array['fecha'][$i]<12){$hora=($array['fecha'][$i]*1).' am';}
  		elseif ($array['fecha'][$i]>12){$hora=($array['fecha'][$i]-12).' pm';}else {$hora=$array['fecha'][$i].' m';}
  		$this->fila($i, $hora, $array['cantidad'][$i], $array['valor'][$i]);
  	}
  }
  /*--------------------------------Porcentaje-----------------------------------------------*/
  private function porcentaje($q, $r)
  {
  	$this->porcentaje = array_sum($q['valor'])*100/$r;
  }
  /*---------------------------------Dibujo----------------------------------------------------*/
  private function init($tiempo)
  {
  	echo '<div class="section">
		<div class="titulos">Ventas del '.$tiempo.'</div>
		<div class="grafico">';
  }
  private function division($d)
  {
  	if($d<5){$ab='s';}else{$ab='';}
  	echo '<div class="dia'.$ab.'" style="width:'.$d.'%;">';
  }
  private function barras($e, $f)
  {
  	if ($e<19){$aa='h';}elseif ($e<25){$aa='m';}else{$aa='';}
  	echo '<div class="barras" style="height:'.$e.'%;"></div>
		 <div class="label_barras'.$aa.'">'.$f.'</div>';
  }
  private function lineas($x, $y)
  {
  	echo '<div class="puntosp" style="bottom:'.$y.'%;right:'.$x.'%;"></div>';
  }
  private function puntos($g, $h)
  {
  	echo '<div class="contenedor_puntos" style="bottom:'.($g-1).'%;">
  			<div class="puntosg"></div>
  			</div>';
  			if(($g+5)>100){
  	echo  '<div class="labelpuntos" style="bottom:'.($g-15).'%;">'.$h.'</div>
			</div>';
  			}
  			else{
	echo  '<div class="labelpuntos" style="bottom:'.($g+5).'%;">'.$h.'</div>
			</div>';
  			}
  }
  private function finGraficas()
  {
  	echo '</div>
  			<div class="tablap">';
  }
  private function escala($j, $k)
  {
  	echo '<div class="fecha" style="width:'.$j.'%;">'.$k.'</div>';
  }
  private function inicioTablas($titulo, $periodo)
  {
  	echo '
  			<div class="section sectiontablesdatas">
	<div class="tablap titulostablesdatas">
	<div class="titulos_tabla">'.$titulo.'</div>
	<div class="titulos_tabla">Ordenes Vendidas</div>
	<div class="titulos_tabla">Acumul. por '.$periodo.' ($)</div>
  			</div>
	<div class="tabla">';
  }
  private function fila($l, $m, $n, $o)
  {
  	echo '<div class="fila'.($l%2).'">';
  	echo '<div class="celda">'.$m.'</div>';
  	echo '<div class="celda">'.$n.'</div>';
  	echo '<div class="celda">'.number_format($o).'</div>';
  	echo '</div>';
  }
  private function totales($p, $w)
  {
  	echo '<div class="fila">
			<div class="total">Total del '.$w.'</div>
			<div class="total">'.array_sum($p['cantidad']).'</div>
			<div class="total">'.number_format(array_sum($p['valor'])).'</div>
			</div>
      </div>
      </div>';
  }
  private function close($s, $z)
  {
    echo '</div></div>';
  }

  private function results($s, $z)
  {
    echo '
    <div class="tablap">
      <div class="meta">Meta del '.$z.'</div>
      <div class="meta">$ '.number_format($s).'</div>
      <div class="meta">Porcentaje del '.$z.'</div>
      <div class="porcentaje">'.number_format($this->porcentaje,2,".",",").'%</div>
    </div>';
  }

  private function vacio()
  {
    echo '
    <div class="tablap">
      <div class="meta"></div>
    </div>';
  }
/*-----------------------------Funciones Index------------------------------*/
  public function graficarMes()
  {
    $this->porcentaje($this->mes, $this->meta);
    $this->results($this->meta, "mes");
    $this->porcentaje($this->dia, $this->metaDia);
    $this->results($this->metaDia, "dia");
    $this->init("Mes");
    $this->graficar($this->mes);
    $this->close($this->meta, "mes");
  	
     
  }
  public function graficarDia()
  {
    $this->vacio();
    $this->vacio();
  	$this->init("D&iacute;a");
  	$this->graficar($this->dia);
  	$this->close($this->metaDia, "d&iacute;a");
  }
  public function last()
  {
  	date_default_timezone_set('America/Bogota');
    echo '<div class="leyenda">
      <div class="leyendas">Actualizado: '.date("d-M H:i:s").'</div>
      <div class="leyendas indicadores" id="indicadores1"><div class="muestrabarras"></div> Valor</div>
      <div class="leyendas indicadores" id="indicadores2"><div class="muestrapuntos"></div> Ordenes</div>
    </div>';
  }
  public function actualizar()
  {
  	$url1=$_SERVER['REQUEST_URI'];
  	//header("Refresh: 39; URL=$url1");
  }
  public function tablasdatos()
  {
    $this->inicioTablas("Hora", "hora");
    $this->tabularDia($this->dia);
    $this->totales($this->dia, "d&iacute;a");

    $this->inicioTablas("Fecha", "d&iacute;a");
    $this->tabularMes($this->mes);
    $this->totales($this->mes, "mes");

    $this->tablacalificaciondiaria();
    $this->tablacalificacionhoras();
  }
  public function tablacalificaciondiaria()
  {
    echo '<div class="section sectiontablesdatas">
            <div class="tablap titulostablesdatas">
              <div class="titulos_tabla">Percepci&oacute;n Prom Diaria</div>
            </div>
            <div class="tabla">'.$this->resultsCalificationdiaria().'</div>
          </div>';
  }
  public function resultsCalificationdiaria()
  {
    $htmlcontent = "";
    foreach ($this->arraycalificaciondiaria as $key => $value) {
      $htmlcontent .= '<div class="fila0">
                        <div class="celda">'.$key.'</div>
                        <div class="celda" id="estrella_'.key($value).'">&#9733; <label class="textstar">'.$value[ key($value) ].'%</label></div>
                      </div>';
    }

    $htmlcontent .= '<div class="fila">
                        <div class="total">Percepcion Mensual</div>
                        <div class="total" id="estrella_'.key($this->porcentajecalificacionmes).'">&#9733; <label class="textstar">'.$this->porcentajecalificacionmes[ key($this->porcentajecalificacionmes) ].'%</label></div>
                      </div>';

    return $htmlcontent;
  }

  public function tablacalificacionhoras()
  {
    echo '<div class="section sectiontablesdatas">
            <div class="tablap titulostablesdatas">
              <div class="titulos_tabla">Percepci&oacute;n Por Horas</div>
            </div>
            <div class="tabla">'.$this->resultsCalificationhoras().'</div>
          </div>';
  }
  
  public function resultsCalificationhoras()
  {
    $htmltablehoras = "";
    $cont = 1;
    foreach ($this->resultadoscalificaciones as $key => $value) {
      if ( $cont > (count($this->resultadoscalificaciones) - 7) ) {
        $htmltablehoras .= '<div class="fila0">
                              <div class="celda">'.$key.'</div>
                              <div class="celda" id="estrella_dor">&#9733; <label class="textstar">'.$value['dor'].'</label></div>
                              <div class="celda" id="estrella_roj">&#9733; <label class="textstar">'.$value['roj'].'</label></div>
                            </div>';
      }
      $cont++;
    }

    /*$htmltablehoras .= '<div class="fila">
                        <div class="total">Percepcion Mensual</div>
                        <div class="total" id="estrella_roj">&#9733; <label class="textstar">50%</label></div>
                      </div>';*/

    return $htmltablehoras;
  }
  public function window()
  {
  	echo '<a href="config.php" class="enlarge">&raquo;</a>
  	 <a href="#" onClick="launchIntoFullscreen(document.documentElement);" class="enlarge">[]</a>
	 <a href="#" onClick="exitFullscreen();" class="restore">_</a>';
  }
  private function readConfig()
  {
  	$result = array();
  	$ar=fopen("report.dat","r") or
  	die("problema de configuracion");
  	while (!feof($ar))
  	{
  		$linea=fgets($ar);
  		$valor = explode(":",$linea);
  		switch ($valor[0]){
  			case "meta":
  				$result['meta'] = substr($valor[1],0,-1);
  				break;
  			case "tema":
  				$result['tema'] = $valor[1];
  				break;
  		}
  	}
  	fclose($ar);
  	return $result;
  }
  public function currentTheme()
  {
  	$config = $this->readConfig();
  	return $config['tema'];
  }
}
?>
