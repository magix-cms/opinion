<?php
function smarty_function_widget_opinion_data($params, $template){
    plugins_Autoloader::register();
    $data = new plugins_opinion_public();
    $assign = isset($params['assign']) ? $params['assign'] : 'opinions';

	switch($params['type']){
		case 'catalog':
			$dataAssign = $data->getItems('product_opinions',$params['idproduct'],'return');
			break;
	}
    $template->assign($assign,$dataAssign);
}