<?php
session_start();
if(isset($_GET["id_aula"]))
{
	if($_GET["id_aula"]!="")
	{
		$id_aula=$_GET["id_aula"];
	}
	else
	{
		$id_aula="";
	}	
}
else
{
	$id_aula="";
}
?>
<script type="text/javascript">
//BLOQUE DE EVENTOS
//--
//--BLOQUE GENERADOR DE LA GRÁFICA--//
var id_aula="<?php echo $id_aula; ?>";
var categorias_eje_x=new Array();
var notas_y=new Array();
var data={id_aula:id_aula};
$.ajax({
			url:"./controladores/controlador.grafica_notas.php",
			type:"POST",
			data:data,
			cache:false,
			error: function()
			{
				console.log(arguments);
			    mensajes(3);
			},
			success: function(html)
			{
				recordset=$.parseJSON(html);
				for(i=0;i<=recordset.length-1;i++)
				{
					categorias_eje_x[i]=recordset[i][3];
					notas_y[i]=parseFloat(recordset[i][5]);			
				}
				var subtitulo=recordset[0][2];
				//----------------------------------------------------------
				$('#container').highcharts({
				        chart: {
				            type: 'column'
				        },
				        title: {
				            text: 'Resumén de Calificaciones'
				        },
				        subtitle: {
				            text: subtitulo
				        },
				        xAxis: {
				            categories:categorias_eje_x
				        },
				        yAxis: {
				            min: 0,
				            title: {
				                text: 'Rainfall (mm)'
				            }
							title: {
							text: '# Calificaciones'
							},
							plotLines: [{
							value: 0,
							width: 1,
							color: '#80808F'
							}]
				        },
				        tooltip: {
				            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
				            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
				                '<td style="padding:0"><b>{point.y:.f} pts</b></td></tr>',
				            footerFormat: '</table>',
				            shared: true,
				            useHTML: true
				        },
				        plotOptions: {
				            column: {
				                pointPadding: 0.2,
				                borderWidth: 0
				            }
				        },
				        series: [{
				            name: 'Contenidos',
				            data:comArr(notas_y),
				          	yAxis:false
				        }]
				    });
				//--------------------------------------------------------------------------------------		
			}//fin de success	
});

//--
//BLOQUE DE FUNCIONES
function comArr(unitsArray) { var outarr = [];
for (var i = 0; i < unitsArray.length; i++) { outarr[i] = [i, unitsArray[i]];
}
return outarr;
} 
</script>
<div class="tabla_body">
	<div id="titulo_eva" name="titulo_eva">
			<h1 class="titulo_eva"><i class="fa fa-bar-chart" style="padding: 1%;"></i></i> Avances seg&uacute;n calificaciones</h1>
	</div>
	 	<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto">
	 	</div>
</div>