<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\export2excel\Export2ExcelBehavior;
use app\models\Exportar;


class ExportarController extends Controller {

    public function behaviors()
	{
	    return [
	        'export2excel' => [
               'class' => Export2ExcelBehavior::className()
          ],
	    ];
	}

	public function actions()
    {
        return [
            'download' => [
                'class' => 'yii\export2excel\DownloadAction',
            ],
        ];
    }

    public function actionExportar()
    {
        $model = new Exportar();
		
		if ( $model->load( Yii::$app->request->post() ) ){
			$model->datos = json_decode( $model->datos, true );
			$model->campos_desc = explode(",", $model->campos_desc);
			
			$resultado = $model->Exportar();
			
			switch ( $model->formato )
			{
				case 1: // Libre Office
					
					header("Content-type: application/vnd.oasis.opendocument.spreadsheet");
					header("Content-Disposition: attachment; filename=\"$model->titulo.ods\";" );
					
					print $resultado;
					
					break;

				case 2: // Excel
					
					if ( count($resultado) > 0 ) {
						$excel_data = Export2ExcelBehavior::excelDataFormat($resultado); // obtengo los datos del array
						$excel_title = $excel_data['excel_title']; // indico los títulos de las celdas según las claves del array
						$excel_ceils = $excel_data['excel_ceils']; // indico los datos de las celdas

						$excel_content = [
							[
								'sheet_name' => 'Listado', // Nombre de pestaña de la hoja de cálculo
								'sheet_title' => $excel_title,
								'ceils' => $excel_ceils,
								'headerColor' => Export2ExcelBehavior::getCssClass("header"),
							],
						];

						$excel_props = [
							'creator' => Yii::$app->param->muni_name,
							'title' => $model->titulo,
							'subject' => '',
							'desc' => $model->detalle,
							'keywords' => '',
							'category' => ''
						];

						$this->export2excel($excel_content, $model->titulo, $excel_props); // parm1: contenido del excel, parm2:nombre del archivo
					}else {
						
						header("Content-type: application/vnd.oasis.opendocument.spreadsheet");
						header("Content-Disposition: attachment; filename=\"$model->titulo.xls\";" );
						
						print '';
						
					}	

					break;

				case 3: // Texto

					header("Content-Type: application/force-download");
					header("Content-Disposition: attachment; filename=\"$model->titulo.txt\";" );

					print $resultado;
					
					break;
			}	
		}
    }

    /*
	Funcion que dibuja la vista de exportar
	titulo = titulo a mostrar
	detalle = descripcion de informe
	action = funcion desde la cual se obtienen los datos
	parametros = parametros de busqueda de datos
    */
    public function exportar( $titulo = '', $detalle = '', $action = '', $parametros = '' ){
	
		$model = new Exportar();
		
		$model->titulo = $titulo;
		$model->detalle = $detalle;
		$model->action = $action;
		$model->parametros = $parametros;
				
		return Yii::$app->controller->renderPartial( '//exportar/exportar', [
            'model' => $model,
            'action' => $action,
            'parametros' => $parametros
        ]);
	}
	
}
