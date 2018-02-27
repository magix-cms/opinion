<?php
function smarty_function_widget_opinion_data($params, $template){
    plugins_Autoloader::register();
    $data = new plugins_opinion_public();
    $assign = isset($params['assign']) ? $params['assign'] : 'opinions';

    $modelTemplate = new frontend_model_template();
    $modelTemplate->addConfigFile(
        array(component_core_system::basePath().'/plugins/opinion/i18n/'),
        array('public_local_'),
        false
    );

    $modelTemplate->configLoad();
	switch($params['type']){
		case 'catalog':
			$dataAssign = $data->getItems('product_opinions',$params['idproduct'],'return');
			$template->assign('statReview',$data->getItems('statReview',$params['idproduct'],'last'));
			break;
	}
    $template->assign($assign,$dataAssign);
}