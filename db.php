<?php
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2016 magix-cms.com support[at]magix-cms[point]com
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
class plugins_opinion_db
{
	/**
	 * @param $config
	 * @$params bool $data
	 * @return mixed|null
	 * @throws Exception
	 */
	public function fetchData($config, $params = false)
	{
		$sql = '';

		if (is_array($config)) {
			if ($config['context'] === 'all') {
				switch ($config['type']) {
					case 'pending':
						$sql = "SELECT 
									opi.*,
									p.id_product,
									pc.name_p,
									pc.url_p,
									cp.id_cat as id_parent,
									cp.name_cat,
									cp.url_cat as url_parent,
									lg.iso_lang
								FROM mc_opinion AS opi
								JOIN mc_catalog AS cat ON ( cat.id_product = opi.id_product )
								JOIN mc_catalog_product AS p ON ( p.id_product = cat.id_product )
								JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
								JOIN mc_catalog_cat AS c ON ( c.id_cat = cat.id_cat)
								JOIN mc_catalog_cat_content AS cp ON ( c.id_cat = cp.id_cat)
								LEFT JOIN mc_lang AS lg ON ( opi.id_lang = lg.id_lang)
								WHERE cat.default_c = 1
								AND opi.status_opinion = 0
								GROUP BY opi.id_opinion
								ORDER BY opi.date_opinion DESC";
						break;
					case 'opinions':
						$limit = $offset ? (($offset -1)*10).', '.(($offset*10)-1) :  '0, 9';
						$sql = "SELECT 
									opi.*,
									cat.idlang,
									cat.titlecatalog,
									cat.urlcatalog,
									p.idproduct,
									c.idclc,
									c.pathclibelle,
									s.idcls,
									s.pathslibelle,
									lg.iso
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS cat ON ( cat.idcatalog = opi.idcatalog )
								JOIN mc_catalog_product AS p ON ( p.idcatalog = cat.idcatalog )
								JOIN mc_catalog_c AS c ON ( c.idclc = p.idclc)
								LEFT JOIN mc_catalog_s AS s ON ( s.idcls = p.idcls)
								LEFT JOIN mc_lang AS lg ON ( cat.idlang = lg.idlang)
								WHERE lg.iso = :lang 
								AND opi.status_opinion = 1
								GROUP BY opi.idopinion
								ORDER BY opi.date_opinion DESC LIMIT $limit";
						break;
					case 'validated':
						$sql = "SELECT 
									opi.*,
									catalog.idlang
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS catalog 
								ON ( catalog.idcatalog = opi.idcatalog )
								WHERE catalog.idlang = :lang 
								AND opi.status_opinion = 1
								AND opi.idcatalog = :id
								GROUP BY opi.idopinion
								ORDER BY opi.date_opinion DESC";
						break;
					case 'product_opinions':
						$sql = "SELECT 
									opi.*,
									c.idlang
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS c ON ( c.idcatalog = opi.idcatalog )
								LEFT JOIN mc_lang AS lg ON ( lg.idlang = c.idlang )
								WHERE lg.iso = :lang 
								AND opi.status_opinion = 1
								AND opi.idcatalog = :id
								GROUP BY opi.idopinion
								ORDER BY opi.date_opinion DESC LIMIT 5";
						break;
				}

				return $sql ? component_routing_db::layer()->fetchAll($sql, $params) : null;
			}
			elseif ($config['context'] === 'one') {
				switch ($config['type']) {
					case 'globalRating':
						$sql = "SELECT ROUND(AVG(opi.rating_opinion),1) as globalRating
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS c ON ( c.idcatalog = opi.idcatalog )
								LEFT JOIN mc_lang AS lg ON ( c.idlang = lg.idlang )
								WHERE lg.iso = :lang
								AND opi.status_opinion = 1";
						break;
					case 'avgRating':
						$sql = "SELECT IFNULL(ROUND(AVG(opi.rating_opinion),1),0) as avgRating
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS catalog ON ( catalog.idcatalog = opi.idcatalog )
								WHERE catalog.idlang = :lang 
								AND opi.status_opinion = 1
								AND opi.idcatalog = :id";
						break;
					case 'pages':
						$sql = "SELECT CEIL(count(opi.idopinion)/10) as last_page
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS c ON ( c.idcatalog = opi.idcatalog )
								WHERE c.idlang = :lang
								AND opi.status_opinion = 1";
						break;
					case 'opinion':
						$sql = "SELECT 
									opi.*,
									pc.name_p,
									lg.id_lang
								FROM mc_opinion AS opi
								JOIN mc_catalog AS cat ON ( cat.id_product = opi.id_product )
								JOIN mc_catalog_product AS p ON ( p.id_product = cat.id_product )
								JOIN mc_catalog_product_content AS pc ON ( p.id_product = pc.id_product )
								JOIN mc_catalog_cat AS c ON ( c.id_cat = cat.id_cat)
								JOIN mc_catalog_cat_content AS cp ON ( c.id_cat = cp.id_cat)
								LEFT JOIN mc_lang AS lg ON ( opi.id_lang = lg.id_lang)
								WHERE cat.default_c = 1
								AND opi.id_opinion = :id
								GROUP BY opi.id_opinion
								ORDER BY opi.date_opinion DESC";
						break;
					case 'statReview':
						$sql = "SELECT IFNULL(ROUND(AVG(opi.rating_opinion),1),0) as avgRating, COUNT(idopinion) as nbReviews
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS catalog ON ( catalog.idcatalog = opi.idcatalog )
								LEFT JOIN mc_lang AS lg ON ( catalog.idlang = lg.idlang )
								WHERE lg.iso = :lang
								AND opi.status_opinion = 1
								AND opi.idcatalog = :id";
						break;
					case 'lang':
						$sql = 'SELECT id_lang FROM mc_lang WHERE iso_lang = :iso';
						break;
				}

				return $sql ? component_routing_db::layer()->fetch($sql, $params) : null;
			}
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @throws Exception
	 */
	public function insert($config, $params = array())
	{
		if (is_array($config)) {
			$sql = '';

			switch ($config['type']) {
				case 'opinion':
					$sql = 'INSERT INTO mc_opinion (id_product,id_lang,pseudo_opinion,email_opinion,msg_opinion,rating_opinion,status_opinion,date_opinion)
                    		VALUE (:id_product,:id_lang,:pseudo,:email,:msg,:rating,0,NOW())';
					break;
			}

			if($sql !== '') component_routing_db::layer()->insert($sql,$params);
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @throws Exception
	 */
	public function update($config, $params = array())
	{
		if (is_array($config)) {
			$sql = '';

			switch ($config['type']) {
				case 'opinion':
					$sql = 'UPDATE mc_opinion
							SET msg_opinion = :msg
							WHERE id_opinion = :id';
					break;
				case 'validate':
					$sql = 'UPDATE mc_opinion
							SET status_opinion = 1
							WHERE id_opinion = :id';
					break;
			}

			if($sql !== '') component_routing_db::layer()->update($sql,$params);
		}
	}

	/**
	 * @param $config
	 * @param array $params
	 * @throws Exception
	 */
	public function delete($config, $params = array())
	{
		if (is_array($config)) {
			$sql = '';

			switch ($config['type']) {
				case 'opinion':
					$sql = 'DELETE FROM mc_opinion WHERE id_opinion = :id';
					break;
			}

			if ($sql !== '') component_routing_db::layer()->delete($sql,$params);
		}
	}


	/**
	 * Select all product in idclc or idcls
	 * @param int $data
	 * @return array
	 */
	public function product($data)
	{
        if(is_array($data)) {
            if (array_key_exists('fetch', $data)) {
                $fetch = $data['fetch'];
            } else {
                $fetch = 'all';
            }
            if (array_key_exists('sort_order', $data)) {
                $sort_order = $data['sort_order'];
            } else {
                $sort_order = 'DESC';
            }
            if (array_key_exists('limit', $data)) {
                $limit_clause = null;
                if (is_int($data['limit'])) {
                    $limit_clause = ' LIMIT ' . $data['limit'];
                }
            }
            if ($fetch == 'all_in') {
                if(array_key_exists('sort_type',$data)) {
                    switch ($data['sort_type']) {
                        case 'id':
                            $order_clause = " ORDER BY opi.idopinion {$sort_order}";
                            break;
                        case 'date':
                            $order_clause = " ORDER BY opi.date_opinion {$sort_order}";
                            break;
                    }
                }else{
                    $order_clause = " ORDER BY opi.date_opinion {$sort_order}";
                }
                $sql = "SELECT opi.*,
					p.idproduct,
					p.idclc, 
					p.idcls,
					cat.urlcatalog, 
					cat.titlecatalog, 
					cat.idlang,
					cat.price,
					cat.desccatalog,
					c.pathclibelle,
					s.pathslibelle,
					cat.imgcatalog,
					lang.iso
				FROM mc_catalog_opinion AS opi
				JOIN mc_catalog_product AS p ON ( opi.idcatalog = p.idcatalog )
				LEFT JOIN mc_catalog AS cat ON ( cat.idcatalog = p.idcatalog )
				LEFT JOIN mc_catalog_c AS c ON ( c.idclc = p.idclc )
				LEFT JOIN mc_catalog_s AS s ON ( s.idcls = p.idcls )
				JOIN mc_lang AS lang ON ( cat.idlang = lang.idlang )
				WHERE opi.status_opinion = 1
				GROUP BY opi.idopinion
				{$order_clause}
                {$limit_clause}";
                return magixglobal_model_db::layerDB()->select($sql);
            }
        }
	}
}