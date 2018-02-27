<?php
require_once('db.php');

class plugins_opinion_public extends plugins_opinion_db {
	/**
	 * master classes
	 */
    protected $template, $header, $message, $settings, $data, $getlang, $origin, $modelDomain, $mail;

	/**
	 * get data
	 */
	public $page;

	/**
	 * post data
	 * @var string
	 */
    public $opinion;

	/**
	 * plugins_opinion_public constructor.
	 */
    public function __construct() {
		$this->template = new frontend_model_template();
		$this->data = new frontend_model_data($this);
		$this->getlang = $this->template->currentLanguage();
		$this->mail = new mail_swift('mail');
		$this->modelDomain = new frontend_model_domain($this->template);
		$this->header = new http_header();
		$this->message = new component_core_message($this->template);
		$this->settings = new frontend_model_setting();

		$formClean = new form_inputEscape();

        if(http_request::isGet('page')){
            $this->page = $formClean::numeric($_GET['page']);
        }

        if(http_request::isPost('opinion')){
            $this->opinion = $formClean::arrayClean($_POST['opinion']);
        }

		if(http_request::isGet('__amp_source_origin')) {
			$this->origin = $formClean->simpleClean($_GET['__amp_source_origin']);
		}
    }

	/**
	 * @return bool
	 */
    private function getValidateData(){
        $required = array('rating','msg','pseudo','email','id_product');

        foreach($required as $k => $v){
            if(!isset($this->opinion[$v])){
				if(isset($this->origin)) {
					$domains = $this->modelDomain->getValidDomains();
					$validOrigins = array("https://cdn.ampproject.org");
					foreach ($domains as $domain) {
						$domain['url_subdomain'] = str_replace('www.','',$domain['url_domain']);
						$validOrigins[] = 'https://'.$domain['url_subdomain'].'.cdn.ampproject.org';
						$validOrigins[] = 'https://'.$domain['url_domain'].'.amp.cloudflare.com';
						$validOrigins[] = 'https://'.$domain['url_domain'];
					}
					$this->header->amp_headers($this->origin,$validOrigins,false);
					$this->header->set_json_headers();
					http_response_code(400);
					print json_encode(array('error'=>'empty'), JSON_FORCE_OBJECT);
				}
				else {
					$this->message->json_post_response(false,'empty');
				}
				return false;
            }
        }
        return true;
    }

	/**
	 * @param $sendTo
	 * @param bool $debug
	 * @return string
	 */
    private function getBodyMail($sendTo = 'admin',$debug = false){
		$cssInliner = $this->settings->getSetting('css_inliner');
		$this->template->assign('getDataCSSIColor',$this->settings->fetchCSSIColor());
		$this->template->assign('opinion',$this->opinion);

		$bodyMail = $this->template->fetch('opinion/mail/'.$sendTo.'.tpl');
		if ($cssInliner['value']) {
			$bodyMail = $this->mail->plugin_css_inliner($bodyMail,array(component_core_system::basePath().'skin/'.$this->template->themeSelected().'/opinion/css' => 'foundation-emails.css'));
		}

		if($debug) {
			print $bodyMail;
		}
		else {
			return $bodyMail;
		}
    }

	/**
	 * @param $admin
	 * @param bool $debug
	 */
    private function sendMail($admin,$debug=false){
    	if ($debug) {
			$this->getBodyMail('admin',true);
		}
		else {
			$allowed_hosts = array_map(function($dom) { return $dom['url_domain']; },$this->modelDomain->getValidDomains());
			if (!isset($_SERVER['HTTP_HOST']) || !in_array($_SERVER['HTTP_HOST'], $allowed_hosts)) {
				header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');
				exit;
			}
			else {
				$noreply = 'noreply@'.str_replace('www.','',$_SERVER['HTTP_HOST']);

				if ($admin) {
					$this->template->configLoad();
					$contact = new plugins_contact_public();
					$lotsOfRecipients = $contact->getContact();

					foreach ($lotsOfRecipients as $recipient){
						$message = $this->mail->body_mail(
							$this->template->getConfigVars('title_mail_admin'),
							array($noreply),
							array($recipient['mail_contact']),
							$this->getBodyMail('admin'),
							false
						);
						$this->mail->batch_send_mail($message);
					}
				}
				else {
					$this->template->configLoad();

					$message = $this->mail->body_mail(
						$this->template->getConfigVars('title_mail_user'),
						array($noreply),
						array($this->opinion['email']),
						$this->getBodyMail('user'),
						false
					);
					$this->mail->batch_send_mail($message);
				}
			}
		}
    }

    /**
     * @param $data
     * @return array
     */
	public function fetchProduct($data){
        return parent::product($data);
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
		return parent::fetchData(array('context'=>$context,'type'=>$type),$params, $this->page);
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
			case 'return':
				return $data;
				break;
			default:
				if ($type == 'opinions') {
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
	private function add($data){
		switch($data['type']){
			case 'opinion':
				$lang = parent::fetchData(array('context' => 'one','type' => 'lang'),array('iso' => $this->getlang));
				$this->opinion['id_lang'] = $lang['id_lang'];

				parent::insert(
					array(
						'type' => $data['type']
					),
					$this->opinion
				);

				if(isset($this->origin)) {
					$domains = $this->modelDomain->getValidDomains();
					$validOrigins = array("https://cdn.ampproject.org");
					foreach ($domains as $domain) {
						$domain['url_subdomain'] = str_replace('www.','',$domain['url_domain']);
						$validOrigins[] = 'https://'.$domain['url_subdomain'].'.cdn.ampproject.org';
						$validOrigins[] = 'https://'.$domain['url_domain'].'.amp.cloudflare.com';
						$validOrigins[] = 'https://'.$domain['url_domain'];
					}
					$this->header->amp_headers($this->origin,$validOrigins,false);
					$this->header->set_json_headers();
					print json_encode(array('status'=>'Success'));
				}
				else {
					//$this->message->json_post_response(true,'add',null,array('method'=>'fetch','template'=>'contact/notify/message.tpl'));
					$this->message->json_post_response(true,'add');
				}
				break;
		}
	}

    /**
     * run
     */
    public function run(){
        $this->template->configLoad();
        if(isset($this->opinion)){
			if($this->getValidateData()) {
				$this->add(
					array(
						'type'=>'opinion'
					)
				);
				$this->sendMail(true);
				$this->sendMail(false);
			}
        }
        else if(isset($_GET['testmail'])){
			$this->sendMail(true,true);
        }
        else {
            $this->getItems('opinions');
            $this->getItems('globalRating',null,'unique');
            $this->getItems('pages',null,'unique');

            $this->template->display('index.tpl');
        }
    }
}