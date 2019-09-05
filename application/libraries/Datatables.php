<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Datatables {
	var $ci;
	var $imported;

	public function __construct() {
		$this->ci = & get_instance ();
	}
	public function generate($table, $columns, $index, $joins, $where, $search, $groupby, $columnsmap) {
		$sLimit = $this->get_paging ();
		$sOrder = $this->get_ordering ( $columns );
		$sWhere = $this->get_filtering ( $columns, $where, $search );
		$rResult = $this->get_display_data ( $table, $columns, $joins, $sWhere, $sOrder, $sLimit,$groupby, $where );
		$tamanho = $this->total_rows ( $table );
		$rResultFilterTotal = $this->get_data_set_length ( $table, $sWhere, $joins ,$groupby);
		$aResultFilterTotal = $rResultFilterTotal->result_array ();
		//var_dump($aResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0]['count(*)'];
		$rResultTotal = $this->get_total_data_set_length ( $table, $index, $sWhere, $joins, $where, $groupby, $columns );
		$aResultTotal = $rResultTotal->result_array ();
		$iTotal = $aResultTotal;
		if ($groupby == "") {
			$aResultTotal = $rResultTotal->result_array ();
			$iTotal = $tamanho;
		} else {
			$iTotal = 1;
		}

		return $this->produce_output ( $columns, $iTotal, $iFilteredTotal, $rResult, $columnsmap );
	}
	// Monta a paginação
	protected function get_paging() {

		if ($this->ci->input->post ( "iDisplayStart" ) && $this->ci->input->post ( "iDisplayLength" ) !== "-1") {
			$sLimit = "LIMIT " . $this->ci->input->post ( "iDisplayLength" ) . " OFFSET " . $this->ci->input->post ( "iDisplayStart" );
		} else {
			$iDisplayLength = $this->ci->input->post ( "iDisplayLength" );
			if (empty ( $iDisplayLength )) {
				$sLimit = "LIMIT " . " 10 OFFSET  0 ";
			} else {
				$sLimit = "LIMIT " . $iDisplayLength . " OFFSET " . $this->ci->input->post ( "iDisplayStart" );
			}
		}
		return $sLimit;
	}
	// Monta a ordenação
	protected function get_ordering($columns) {
		$sOrder = "";



		if ($this->ci->input->post ( "iSortCol_0" )) {
			$sOrder = "ORDER BY ";
			for($i = 0; $i < intval ( $this->ci->input->post ( "iSortingCols" ) ); $i ++){

				$sOrder .= $columns [intval ( $this->ci->input->post ( "iSortCol_" . $i ) )] . " " . $this->ci->input->post ( "sSortDir_" . $i )
				 . ", ";

				if($sOrder=='ORDER BY IF(`read`=1, "Yes", "No") desc, '){
				    $sOrder='ORDER BY tbl_statements.`read`  desc, ';
				}
			}
			$sOrder = substr_replace ( $sOrder, "", - 2 );
		}
		return $sOrder;
	}
	// Ajusta o filtro
	protected function get_filtering($columns, $where, $search) {
		$sWhere = "WHERE 1=1 ";

		if ($this->ci->input->post ( "sSearch" ) != '' and $this->ci->input->post ( "sSearch" ) != '0') {
		    //var_dump($columns [$i]);
			$sWhere = "WHERE (";
			for($i = 0; $i < count ( $columns ); $i ++) {


				if ($columns [$i] == 'IF(tbl_job_vacancy.status = 1,"Active" ,"Hold") AS STATUS') {
					$columns [$i] = 'tbl_job_vacancy.status';
				} elseif ($columns [$i] == 'tbl_job_candidate.id as cid') {
					$columns [$i] = 'tbl_job_candidate.id';
				}
				  elseif ($columns [$i] == 'tbl_leave_type.name AS leavename') {
				  $columns [$i] = 'tbl_leave_type.name';

				  }
				  elseif ($columns [$i] == 'tbl_job_vacancy.name as pos') {
				$columns [$i] = 'tbl_job_vacancy.name';

			}elseif ($columns [$i] == 't2.emp_firstname as g') {
				$columns [$i] = 't2.emp_firstname';

			}elseif ($columns [$i] == 't3.emp_firstname as c') {
				$columns [$i] = 't3.emp_firstname';

			}elseif ($columns [$i] == 't4.emp_firstname as a') {
				$columns [$i] = 't4.emp_firstname';

			}elseif ($columns [$i] == 't4.emp_firstname as a') {
				$columns [$i] = 't4.emp_firstname';

			}elseif ($columns [$i] == 'IF(READ = 1, "Yes", "No")') {
			    $columns [$i] = 'read';

			}
			elseif ($columns [$i] == 'tbl_user_role.name as role_code') {
			    $columns [$i] = 'tbl_user_role.name';

			}
				else
					$columns [$i] = $columns [$i];
				$sWhere .= $columns [$i] . " LIKE '%" . $this->ci->input->post ( "sSearch" ) . "%' OR ";
			}

			$sWhere = substr_replace ( $sWhere, "", - 3 );
			$sWhere .= ")";
		}

		/*
		 * Individual column filtering
		 */

		for($i = 0; $i < count ( $columns ); $i ++) {
			// var_dump($this->ci->input->post ( 'sSearch_' . $i ));
			//var_dump($columns [$i]);
			if ($columns [$i] == 'IF(tbl_job_vacancy.status = 1,"Active" ,"Hold") AS STATUS') {
				$columns [$i] = 'tbl_job_vacancy.status';
			} elseif ($columns [$i] == 'tbl_job_candidate.id as cid') {
				$columns [$i] = 'tbl_job_candidate.id';

			}
			elseif ($columns [$i] == 'tbl_nationality.name as nname') {
				$columns [$i] = 'tbl_nationality.name';

			}
			elseif ($columns [$i] == 'IF(READ = 1, "Yes", "No")') {
			    $columns [$i] = 'read';

			}
			elseif ($columns [$i] == 'tbl_leave_type.name as lname') {
				$columns [$i] = 'tbl_leave_type.name';

			}

			elseif ($columns [$i] == 'tbl_leave_type.name as leavename') {
				$columns [$i] = 'tbl_leave_type.name';

			}
			elseif ($columns [$i] == 'tbl_job_vacancy.name as pos') {
				$columns [$i] = 'tbl_job_vacancy.name';

			}elseif ($columns [$i] == 't2.emp_firstname as g') {
				$columns [$i] = 't2.emp_firstname';

			}elseif ($columns [$i] == 't3.emp_firstname as c') {
				$columns [$i] = 't3.emp_firstname';

			}elseif ($columns [$i] == 't4.emp_firstname as a') {
				$columns [$i] = 't4.emp_firstname';

			}
			elseif ($columns [$i] == 'tbl_user_role.name as role_code') {
			    $columns [$i] = 'tbl_user_role.name';

			}
			else {
				$columns [$i] = $columns [$i];
			}

			if ($this->ci->input->post ( 'sSearch_' . $i ) != '') {
				// var_dump($columns [$i]);
				if ($where == "") {
					$where = "WHERE ";
				} else {
					$where .= " AND ";
				}
				// var_dump($columns [$i]);
				$where .= $columns [$i] . " LIKE '%" . $this->ci->input->post ( 'sSearch_' . $i ) . "%' ";
			}
			// var_dump($where);
		}
		//var_dump($sWhere . $where);
		return $sWhere . $where;

	}
	// Popula a tabela
	protected function get_display_data($table, $columns, $joins, $sWhere, $sOrder, $sLimit,$groupby) {

		$res = " SELECT " . str_replace ( " , ", " ", implode ( ", ", $columns ) ) . " FROM  $table  $joins  $sWhere  $groupby $sOrder  $sLimit ";
//echo ($res);

		return $this->ci->db->query ( $res );
	}
	// Calcula o número de registros da tabela
	protected function total_rows($table) {
		$sql = "select count(*) as qtd from $table ";
		$res = $this->ci->db->query ( $sql );
		$res = $res->row ();
		return $res->qtd;
	}

	protected function get_data_set_length($table, $sWhere, $joins,$groupby) {
		$sql = "select count(*) from $table $joins $sWhere $groupby";
		$res = $this->ci->db->query ( $sql );
		return $res;
	}

	protected function get_total_data_set_length($table, $index, $sWhere, $joins, $where, $groupby, $columns) {
		if ($groupby == "") {
			$Consulta = "SELECT COUNT(*) FROM $table $joins $sWhere ";
			$DatoSalida = $this->ci->db->query ( $Consulta );
		} else {
			$ConsultaSql = "SELECT " . implode ( ", ", $columns ) . " FROM $table $joins $sWhere $groupby ";
			$DatoSalida = $this->ci->db->query ( $ConsultaSql );

		//	$DatoSalida = $Consulta->num_rows ();
		}
		//var_dump($DatoSalida);
		return $DatoSalida;
	}
	// Prepara os dados para saída
	protected function produce_output($columns, $iTotal, $iFilteredTotal, $rResult, $columnsmap) {
		$aaData = array ();
		if ($this->ci->input->post ( "sEcho" ) == '0' or $this->ci->input->post ( "sEcho" ) == '1')
			$i = 0;
		else
			$i = $this->ci->input->post ( "iDisplayStart" );
		foreach ( $rResult->result_array () as $row_key => $row_val ) {
			$j = 0;
			foreach ( $row_val as $col_key => $col_val ) {

				if ($row_val [$col_key] == "version"){

					$aaData [$row_key] [$col_key] = ($aaData [$row_key] [$col_key] == 0) ? "-" : $col_val;
				}
				else if ($columnsmap [$j] == "checkbox"){
					$primaryId = $col_val;
					$aaData [$row_key] [] = "<input class='checkbox' type='checkbox' name='checkbox[]' id='checkbox_$i' value='$primaryId'>";
				}
				else if ($columnsmap [$j] == "viewedit") {
					$primaryId = $col_val;
					if(substr($primaryId,0,2)=='00'){
						$primaryId = substr($primaryId,2);
						$aaData [$row_key] [] = '';
					}
					else{
					$aaData [$row_key] [] = '<a id="' . $col_val . '" class="viewEdit" style="cursor:pointer">
					<img name="Edit" src="'.base_url().'img/edit.jpg" width="20" height="20" alt="Edit" /></a>';
					}
				}
				else if ($columnsmap [$j] == "delete") {
					$primaryId = $col_val;
					$aaData [$row_key] [] = '<a id="' . $col_val . '" class="delete" style="cursor:pointer"><img name="Delete" src="'.base_url().'img/delete.png" height="25" width="25" alt="Edit"></a>';
				}
					else if ($columnsmap [$j] == "process") {
					$primaryId = $col_val;
					$aaData [$row_key] [] = '<a id="' . $col_val . '" class="process" style="cursor:pointer"><img name="Process" src="'.base_url().'img/process.jpg" height="25" width="25" alt="Edit"></a>';
				}


				else if ($columnsmap [$j] == "email") {
					$primaryId = $col_val;
					$aaData [$row_key] [] = '<u><a href="mailto:' . $col_val . '" class="email" style="cursor:pointer">' . $col_val . '</a></u>';
				}
				else if ($columnsmap [$j] == "user"){

					$aaData [$row_key] [] = '<a id="' . $primaryId . '" class="user" style="cursor:pointer">' . $col_val . '</a>';
				}
				else if ($columnsmap [$j] == "download"){
				$secid=$col_val;
					$aaData [$row_key] [] = '<a id="' . $col_val . '" class="download" style="cursor:pointer"><img name="Download" src="'.base_url().'img/download.jpg" height="20" width="20" alt="upload/download" style="z-index:-1";></a>';
				}
				else if ($columnsmap [$j] == "primaryID"){
				$secid=$col_val;
				$aaData [$row_key] [] = $col_val;
				}

				else if ($columnsmap [$j] == "slno")
					$aaData [$row_key] [] = $i + 1;
					else if ($columnsmap [$j] == "href") {

					$aaData [$row_key] [] = '<a id="' . $secid . '" class="href" style="cursor:pointer" name="' . $primaryId . '">' . $col_val . '</a>';
				}
				else {
					switch ($row_val [$col_key]) {
						default :
							$aaData [$row_key] [] = $col_val;
							break;
					}

				}
				$j ++;
			}
			$i ++;
		}
		$sOutput = array ("sEcho" => intval ( $this->ci->input->post ( "sEcho" ) ), "iTotalRecords" => $iTotal, "iTotalDisplayRecords" => $iFilteredTotal, "aaData" => $aaData );
		return json_encode ( $sOutput );
	}
}