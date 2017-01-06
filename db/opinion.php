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

 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------

 # DISCLAIMER

 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
class database_plugins_opinion
{
	/**
	 * Checks if the tables of the plugins are installed
	 * @access protected
	 * return integer
	 */
	protected function c_show_tables(){
		$tables = array(
			'mc_catalog_opinion'
		);

		$i = 0;
		do {
			$t = magixglobal_model_db::layerDB()->showTable($tables[$i]);
			$i++;
		} while($t && $i < count($tables));

		return $t;
	}

	/**
	 * Checks if the requested table is installed
	 * @param $t
	 * @return integer
	 */
	protected function c_show_table($t){
		return magixglobal_model_db::layerDB()->showTable($t);
	}

	/**
	 * @param $config
	 * @param null $data
	 * @param null $offset
	 * @return array|null
	 */
	public function fetchData($config,$data = null,$offset = null){
		if (is_array($config)) {
			$sql = '';
			$params = $data == null ? false : $data;

			if ($config['context'] === 'all' || $config['context'] === 'return') {
				if ($config['type'] === 'pending') {
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
							WHERE cat.idlang = :lang 
							AND opi.status_opinion = 0
							GROUP BY opi.idopinion
							ORDER BY opi.date_opinion DESC";
				}
				elseif ($config['type'] === 'opinions') {
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
				}
				elseif ($config['type'] === 'validated') {
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
				}
				elseif ($config['type'] === 'product_opinions') {
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
							ORDER BY opi.date_opinion DESC";
				}

				return $sql ? magixglobal_model_db::layerDB()->select($sql,$params) : null;
			}
			elseif ($config['context'] === 'unique' || $config['context'] === 'last') {
				if ($config['context'] === 'unique') {
					if ($config['type'] === 'globalRating') {
						$sql = "SELECT ROUND(AVG(opi.rating_opinion),1) as globalRating
							FROM mc_catalog_opinion AS opi
							JOIN mc_catalog AS c ON ( c.idcatalog = opi.idcatalog )
							LEFT JOIN mc_lang AS lg ON ( c.idlang = lg.idlang )
							WHERE lg.iso = :lang
							AND opi.status_opinion = 1";
					}
					elseif ($config['type'] === 'avgRating') {
						$sql = "SELECT ROUND(AVG(opi.rating_opinion),1) as avgRating
							FROM mc_catalog_opinion AS opi
							JOIN mc_catalog AS catalog ON ( catalog.idcatalog = opi.idcatalog )
							WHERE catalog.idlang = :lang 
							AND opi.status_opinion = 1
							AND opi.idcatalog = :id";
					}
					elseif ($config['type'] === 'pages') {
						$sql = "SELECT CEIL(count(opi.idopinion)/10) as last_page
								FROM mc_catalog_opinion AS opi
								JOIN mc_catalog AS c ON ( c.idcatalog = opi.idcatalog )
								WHERE c.idlang = :lang
								AND opi.status_opinion = 1";
					}
				}
				else {
					if ($config['type'] === 'opinion') {
						$sql = "SELECT 
								opi.*,
								catalog.idlang,
								catalog.titlecatalog
							FROM mc_catalog_opinion AS opi
							JOIN mc_catalog AS catalog 
							ON ( catalog.idcatalog = opi.idcatalog )
							WHERE catalog.idlang = :lang 
							AND opi.status_opinion = 0
							AND opi.idopinion = :id
							GROUP BY opi.idopinion
							ORDER BY opi.date_opinion DESC";
					}
				}

				return $sql ? magixglobal_model_db::layerDB()->selectOne($sql,$params) : null;
			}
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

	/**
	 * @param $config
	 * @param bool $data
	 */
	public function insert($config,$data = false){
		if (is_array($config)) {
			if ($config['type'] === 'opinion') {
				$sql = 'INSERT INTO mc_catalog_opinion (idcatalog,pseudo_opinion,email_opinion,msg_opinion,rating_opinion,status_opinion,date_opinion)
                    VALUE (:idcatalog,:pseudo,:email,:msg,:rating,0,NOW())';
				magixglobal_model_db::layerDB()->insert($sql,
					array(
						':idcatalog' => $data['idcatalog'],
						':pseudo' => $data['pseudo'],
						':email' => $data['email'],
						':msg' => $data['msg'],
						':rating' => $data['rating']
					));
			}
		}
	}

	/**
	 * @param $config
	 * @param bool $data
	 */
	public function update($config,$data = false){
		if (is_array($config) && is_array($data)) {
			if ($config['type'] === 'opinion') {
				$sql = 'UPDATE mc_catalog_opinion
						SET msg_opinion = :msg
						WHERE idopinion = :id';
				magixglobal_model_db::layerDB()->update($sql,$data);
			}
			elseif ($config['type'] === 'validate') {
				$sql = 'UPDATE mc_catalog_opinion
						SET status_opinion = 1
						WHERE idopinion = :id';
				magixglobal_model_db::layerDB()->update($sql,$data);
			}
		}
	}

	/**
	 * @param $config
	 * @param bool $data
	 */
	public function delete($config,$data = false)
	{
		if (is_array($config) && is_array($data)) {
			if($config['type'] === 'opinion') {
				$sql = 'DELETE FROM mc_catalog_opinion WHERE idopinion = :id';
				magixglobal_model_db::layerDB()->delete($sql,$data);
			}
		}
	}
}