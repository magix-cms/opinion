<?php
require_once('db/opinion.php');

class plugins_opinion_public extends database_plugins_opinion{
	/**
	 * master classes
	 */
    protected $template, $header, $message, $setting, $mail;

	/**
	 * get data
	 */
	public $lang, $page;

	/**
	 * post data
	 * @var string
	 */
    public $opinion;

	/**
	 * plugins_opinion_public constructor.
	 */
    public function __construct(){
		$this->template = new frontend_controller_plugins;
		$this->header = new magixglobal_model_header;
		$this->setting = frontend_model_setting::select_uniq_setting('css_inliner');
		$this->mail = new magixglobal_model_mail('mail');
		if(class_exists('frontend_model_message')){
			$this->message = new frontend_model_message();
		}

		$rFilter = new magixcjquery_filter_request();
		$hforms = new magixcjquery_form_helpersforms();

        if($rFilter::isGet('strLangue')){
            $this->lang = $hforms::inputClean($_GET['strLangue']);
        }
        if($rFilter::isGet('page')){
            $this->page = $hforms::inputNumeric($_GET['page']);
        }

        if($rFilter::isPost('opinion')){
            $this->opinion = $hforms::arrayClean($_POST['opinion']);
        }
    }

	/**
	 * @return bool
	 */
    private function getValidateData(){
        $required = array('rating','msg','pseudo','email','idcatalog');

        foreach($required as $k => $v){
            if(!isset($this->opinion[$v])){
				$this->header->set_json_headers();
				$this->message->json_post_response(false,'empty');
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
    private function getBodyMail($sendTo,$debug = false){
		$fetchColor = new frontend_db_setting();
		$this->template->assign('getDataCSSIColor',$fetchColor->fetchCSSIColor());

		if($debug) {
			$bodyMail = $this->template->fetch('mail/admin.tpl');

			if ($this->setting['setting_value']) {
				print $this->mail->plugin_css_inliner($bodyMail,array('/opinion/css' => 'foundation-emails.css'));
			} else {
				print $bodyMail;
			}
		} else {
			$this->template->assign('opinion',$this->opinion);
			$bodyMail = $this->template->fetch('mail/'.$sendTo.'.tpl');

			if ($this->setting['setting_value']) {
				return $this->mail->plugin_css_inliner($bodyMail,array('/opinion/css' => 'foundation-emails.css'));
			} else {
				return $bodyMail;
			}
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
			if ($admin) {
				$this->template->configLoad();
				$core_mail = new magixglobal_model_mail('mail');
				$contact = new plugins_contact_public();
				$lotsOfRecipients = $contact->getContact();

				foreach ($lotsOfRecipients as $recipient){
					$message = $core_mail->body_mail(
						$this->template->getConfigVars('title_mail_admin'),
						array($this->template->getConfigVars('noreply_mail')),
						array($recipient['mail_contact']),
						$this->getBodyMail('admin'),
						false
					);
					$core_mail->batch_send_mail($message);
				}
			}
			else {
				$this->template->configLoad();
				$core_mail = new magixglobal_model_mail('mail');

				$message = $core_mail->body_mail(
					$this->template->getConfigVars('title_mail_user'),
					array($this->template->getConfigVars('noreply_mail')),
					array($this->opinion['email']),
					$this->getBodyMail('user'),
					false
				);
				$core_mail->batch_send_mail($message);
			}
		}
    }

    /**
     * @param null $idclc
     * @param int $idcls
     * @param null $limit
     * @param string $sort
     * @return array
     */
	public function fetchProduct($idclc=null,$idcls=0,$limit=null,$sort='id'){
        return parent::product($idclc,$idcls,$limit,$sort);
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
				parent::insert(
					array(
						'type' => $data['type']
					),
					$this->opinion
				);

				$this->header->set_json_headers();
				$this->message->json_post_response(true,'add',null,array('template'=>'notify/message.tpl'));
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