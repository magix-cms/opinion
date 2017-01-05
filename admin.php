<?php
require_once('db/opinion.php');

class plugins_opinion_admin extends database_plugins_opinion {
	/**
	 * master classes
	 */
    protected $header, $template, $message;

	/**
	 * get data
	 */
	public $plugin, $lang, $action, $tab, $edit;

	/**
	 * post data
	 */
    public $id, $msg_opinion;

	/**
	 * plugins_opinion_admin constructor.
	 */
    public function __construct(){
		$this->header= new magixglobal_model_header();
		$this->template = new backend_controller_plugins();
		if(class_exists('backend_model_message')){
			$this->message = new backend_model_message();
		}

		$rFilter = new magixcjquery_filter_request();
		$vFilter = new magixcjquery_filter_isVar();
		$hForms = new magixcjquery_form_helpersforms();

        // Global
        if($rFilter->isGet('getlang')){
            $this->lang = $vFilter->isPostNumeric($_GET['getlang']);
        }
        if($rFilter->isGet('edit')){
            $this->edit = $vFilter->isPostNumeric($_GET['edit']);
        }
		if($rFilter->isGet('tab')){
			$this->tab = $hForms->inputClean($_GET['tab']);
		}
        if($rFilter->isGet('action')){
            $this->action = $hForms->inputClean($_GET['action']);
        }
		if($rFilter->isGet('id')){
			$this->id = (integer) $vFilter->isPostNumeric($_GET['id']);
		}
		elseif($rFilter->isPost('id')){
			$this->id = (integer)$vFilter->isPostNumeric($_POST['id']);
		}
		if($rFilter->isPost('msg_opinion')){
			$this->msg_opinion = $hForms->inputClean($_POST['msg_opinion']);
		}

        // Dedicated
        if($rFilter->isGet('plugin')){
            $this->plugin = $hForms->inputClean($_GET['plugin']);
        }
    }

	/**
	 * Configuration du plugin
	 * @return array
	 */
	public function setConfig(){
		return array(
			'url'=> array(
				'lang'=>'list',
				'name'=>'Témoignages'
			)
		);
	}

	/**
	 * @access private
	 * Installing mysql database plugin
	 */
	private function install_table()
	{
		if (parent::c_show_tables() == 0) {
			$this->template->db_install_table('db.sql', 'request/install.tpl');
		} else {
			return true;
		}
	}

	/**
	 * Retrieve product url
	 * @param $data
	 */
	private function getProductUrl(&$data)
	{
		$ModelRewrite = new magixglobal_model_rewrite();
		foreach ($data as $i => $row) {
			$subcat['id']   = (isset($row['idcls'])) ? $row['idcls'] : null;
			$subcat['name'] = (isset($row['pathslibelle'])) ? $row['pathslibelle'] : null;

			$data[$i]['url'] =
				$ModelRewrite->filter_catalog_product_url(
					$row['iso'],
					$row['pathclibelle'],
					$row['idclc'],
					$subcat['name'],
					$subcat['id'],
					$row['urlcatalog'],
					$row['idproduct'],
					true
				);
		}
    }

	/**
	 * Retrieve data
	 * @param string $context
	 * @param string $type
	 * @param string|int|null $id
	 * @return mixed
	 */
	private function setItems(&$context, $type, $id = null) {
		$params = array(':lang' => $this->lang);
		if($id) {
			$params[':id'] = $id;
			$context = $context ? $context : 'unique';
		} else {
			$context = $context ? $context : 'all';
		}
		return parent::fetchData(array('context'=>$context,'type'=>$type),$params);
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $context
	 * @param string $type
	 * @param string|int|null $id
	 * @return mixed
	 */
	public function getItems($type, $id = null, $context = null) {
		$data = $this->setItems($context, $type, $id);
		switch ($context) {
			case 'last':
				return $data;
				break;
			default:
				if ($type == 'pending') {
					$this->getProductUrl($data);
				}
				$varName = $type;
				$this->template->assign($varName,$data);
		}
	}

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function upd($data) {
		switch($data['type']){
			case 'opinion':
				parent::update(
					array(
						'type' => $data['type']
					),
					array(
						':id' => $this->id,
						':msg' => $this->msg_opinion
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',array(':id' => $this->id));
				break;
			case 'validate':
				parent::update(
					array(
						'type' => $data['type']
					),
					array(
						':id' => $this->id
					)
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'update',array(':id' => $this->id));
				break;
		}
	}

	/**
	 * Insertion de données
	 * @param $data
	 */
	private function del($data) {
		switch($data['type']){
			case 'opinion':
				parent::delete(
					array(
						'type' => $data['type']
					),
					$data['data']
				);
				$this->header->set_json_headers();
				$this->message->json_post_response(true,'delete',$data['data']);
				break;
		}
	}

	/**
	 * product dispatcher
	 * @param $plugin
	 * @param $getlang
	 * @param $edit
	 */
	public function catalog_product($plugin,$getlang,$edit)
	{
		if (isset($this->plugin)) {
			if (self::install_table() == true) {
				$this->getItems('validated',$this->edit,'all');
				$this->getItems('avgRating',$this->edit);
				$this->template->display('catalog.tpl');
			}
			else {
				$this->template->display('install.tpl');
			}
		}
	}

	/**
	 * Dispatcher
	 */
    public function run() {
        if (self::install_table()) {
            if (isset($this->action)) {
            	switch ($this->action) {
					case 'edit':
						if($this->id) {
							if(!empty($this->msg_opinion)){
								$this->upd(
									array(
										'type' => 'opinion'
									)
								);
							}
						} else {
							$this->header->set_json_headers();
							print json_encode($this->getItems('opinion',$this->edit,'last'));
						}
						break;
					case 'validate':
						if($this->id) {
							$this->upd(
								array(
									'type' => 'validate'
								)
							);
						}
						break;
					case 'delete':
						if ($this->id) {
							$this->del(
								array(
									'type'=>'opinion',
									'data'=>array(
										':id' => $this->id
									)
								)
							);
						}
						break;
				}
            }
            else {
            	if($this->tab) {
					$this->template->display('about.tpl');
				} else {
					$this->getItems('pending');
					$this->template->display('index.tpl');
				}
			}
        }
    }
}