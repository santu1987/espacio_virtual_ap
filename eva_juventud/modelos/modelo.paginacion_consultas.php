<?php
class paginacion 
{
	public $actual;
	public $num_rows;
	public $limit=5;
	public $cuantos_enlaces;
	public $pagVisibles;
	public $primera_pag;
	public $ultima_pag;
    public $nom_fun;
	function __construct($actual,$num_rows,$nom_fun)
	{
		$this->actual=$actual;
		$this->num_rows=$num_rows;
        $this->nom_fun=$nom_fun;
	}
////////////////////////////////////////////////////	
	function crear_paginacion()
	{
		//limite por paginas: $this->limit... 
		//cuantos datos hay: $this->num_rows...
		//pagina actual: $this->actual...
		//////calculando los enlaces....
		//cuantos enlaces
		$this->cuantos_enlaces=ceil($this->num_rows/$this->limit);	
		$this->pagVisibles = 5;
        if($this->actual <= $this->pagVisibles)
        {
            $this->primera_pag = 0;   
        }else
        {
            $this->primera_pag = $this->actual - $this->pagVisibles; 
        }
        if($this->actual+ $this->pagVisibles <= $this->cuantos_enlaces)
        {
            $this->ultima_pag = $this->actual + $this->pagVisibles;
        }else
        {
            $this->ultima_pag = $this->cuantos_enlaces;
        }
        ///////////////////////////////////////////////////////////
        //armando la paginacion
       //valido que si es la 1era no me monte links si es >1 calcula a cual debe ir si pulsa el link determinado...
        if($this->actual > 0)
        {
        	$primera=' <li><a href="#" " onclick="'.$this->nom_fun.'(0,'.$this->limit.',0)">«</a></li>';
        }
        else
        {
        	$primera.=' <li><a  href= "#" > « </a></li>';
        }	
        //para el ultimo
        if($this->actual < $this->cuantos_enlaces)
        {
            $ultima.='<li><a href="#" class="button round" onclick="'.$this->nom_fun.'('.((($this->cuantos_enlaces)-1)*$this->limit).','.$this->limit.','.(($this->cuantos_enlaces)-1).')">»</a></li>';
        }
        else
        {
            $ultima.=' <li><a  href= "#" > » </a></li>';
        }   
        for($i=$this->primera_pag; $i<=$this->ultima_pag-1; $i++) 
        {
            $a=$i+1;
            if($i == $this->actual)
            {
                $cuerpo_pag.='<li class="active" onclick="'.$this->nom_fun.'('.$i.','.$this->limit.','.$i.')"><a href="#">'.$a.'<span class="sr-only">(current)</span></a></li>';
            }
            else
            {
                $cuerpo_pag.='<li><a href="#" onclick="'.$this->nom_fun.'('.(($i)*$this->limit).','.$this->limit.','.$i.')">'.$a.'<span class="sr-only">(current)</span></a></li>';
            }    
        }
        /*if($this->actual< $this->cuantos_enlaces)
        {

        }    
        $html .= ($actual_pag < $totalPag) ? 
        ' <a href="#" class="button round" onclick="paginate('.(($actual_pag+1)*$limit).','.$limit.')">Siguiente</a>' : 
        ' <a href="#" class="button round disabled">Siguiente</a>';
        $html .= ($actual_pag < $totalPag) ? 
        ' <a href="#" class="button round" onclick="paginate('.(($totalPag)*$limit).','.$limit.')">Última</a>' : 
        ' <a href="#" class="button round disabled">Última</a>';
        $html .= '</p>';*/
        $html=$primera.$cuerpo_pag.$ultima;
        return $html;
        ///////////////////////////////////////////////////////////
	}
///////////////////////////////////////////////
} 
?>