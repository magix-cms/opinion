<?php
require_once('db.php');

class plugins_opinion_admin extends plugins_opinion_db {
	/**
	 * master classes
	 */
    protected $header, $template, $message, $data;

	/**
	 * get data
	 */
	public $plugin, $lang, $action, $tabs, $edit;

	/**
	 * post data
	 */
    public $id, $opinion;

	/**
	 * plugins_opinion_admin constructor.
	 */
    public function __construct(){
		$this->template = new backend_model_template();
		$this->header= new http_header();
		$this->message = new component_core_message($this->template);
		$this->data = new backend_model_data($this);
		$formClean = new form_inputEscape();

        // --- Global
        if(http_request::isGet('getlang')) {
            $this->lang = $formClean->numeric($_GET['getlang']);
        }
        if(http_request::isGet('edit')) {
            $this->edit = $formClean->numeric($_GET['edit']);
        }
		if(http_request::isGet('tabs')) {
			$this->tabs = $formClean->simpleClean($_GET['tabs']);
		}
        if(http_request::isGet('action')) {
            $this->action = $formClean->simpleClean($_GET['action']);
        }

        // --- Post
		if(http_request::isGet('id')) {
			$this->id = (integer) $formClean->numeric($_GET['id']);
		}
		elseif(http_request::isPost('id')) {
			$this->id = (integer)$formClean->numeric($_POST['id']);
		}

		if(http_request::isPost('opinion')) {
			$this->opinion = $formClean->arrayClean($_POST['opinion']);
		}
    }

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName()
	{
		return $this->template->getConfigVars('opinion_plugin');
	}

	/**
	 * Retrieve product url
	 * @param $data
	 */
	private function getProductUrl($data)
	{
		$ModelRewrite = new component_routing_url();
		foreach ($data as $i => $row) {
			$data[$i]['url'] = $ModelRewrite->getBuildUrl(
				array(
					'type' => 'product',
					'iso' => $row['iso_lang'],
					'id_parent' => $row['id_parent'],
					'url_parent' => $row['url_parent'],
					'id' => $row['id_product'],
					'url' => $row['url_p']
				)
			);
		}
		return $data;
    }

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return mixed
	 * @throws Exception
	 */
	private function getItems($type, $id = null, $context = null, $assign = true) {
		return $this->data->getItems($type, $id, $context, $assign);
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
						'id' => $this->id,
						'msg' => $this->opinion['msg']
					)
				);
				$this->message->json_post_response(true,'update',array('id' => $this->id));
				break;
			case 'validate':
				parent::update(
					array(
						'type' => $data['type']
					),
					array(
						'id' => $this->id
					)
				);
				$this->message->json_post_response(true,'update',array('id' => $this->id));
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
				$this->message->json_post_response(true,'delete',$data['data']);
				break;
		}
	}

	/**
	 * Dispatcher
	 */
	public function run() {
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
					}
					else {
						$this->header->set_json_headers();
						print json_encode($this->getItems('opinion',$this->edit,'one'));
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
					if($this->id) {
						$this->del(
							array(
								'type'=>'opinion',
								'data'=>array(
									'id' => $this->id
								)
							)
						);
					}
					break;
			}
		}
		else {
			$pending = $this->getItems('pending',null,'all',false);
			$this->template->assign('pending',$this->getProductUrl($pending));
			$this->template->display('index.tpl');
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
}