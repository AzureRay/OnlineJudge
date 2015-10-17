<?php
	function getexamsearch(){
		$search = I('get.search','');
		if($search!='')
			$sql = "`visible`='Y' AND (`creator` like '%$search%' or `title` like '%$search%')";
		else
			$sql = "`visible`='Y'";
		return array('search'=>$search,
					'sql'=>$sql);
	}

	function problemshow($problem,$searchsql){
		if($problem<0||$problem>2)
			$problem=0;
		if(!checkAdmin(1)&&$problem==2)
			$problem=0;
		if($searchsql==""){
			if($problem==0||checkAdmin(1))
				$prosql="`isprivate`='$problem'";
			else{
				$user=$_SESSION['user_id'];
				$prosql="`isprivate`='$problem' AND `creator` like '$user'";
			}
		}
		else{
			if($problem==0||checkAdmin(1))
				$prosql=" AND `isprivate`='$problem'";
			else{
				$user=$_SESSION['user_id'];
				$prosql=" AND `isprivate`='$problem' AND `creator` like '$user'";
			}
		}
		return $prosql;
	}

	function getproblemsearch(){
		$search = I('get.search','');
		if($search!='')
			$sql = "(`creator` like '%$search%' or `point` like '%$search%')";
		else
			$sql = "";
		$problem = I('get.problem',0,'intval');
		$prosql = problemshow($problem,$sql);
		$sql.=$prosql;
		return array('search'=>$search,
					'problem'=>$problem,
					'sql'=>$sql);
	}

	function set_get_key(){
		$_SESSION['getkey']=strtoupper(substr(MD5($_SESSION['user_id'].rand(0,9999999)),0,10));
		return $_SESSION['getkey'];
	}

	function check_get_key(){
		if ($_SESSION['getkey']!=$_GET['getkey'])
			return false;
		return true;
	}

	function set_post_key(){
		$_SESSION['postkey']=strtoupper(substr(MD5($_SESSION['user_id'].rand(0,9999999)),0,10));
		return $_SESSION['postkey'];
	}

	function check_post_key(){
		if ($_SESSION['postkey']!=$_POST['postkey'])
			return false;
		return true;
	}

	function cutstring($str){
		$len = C('cutlen');
		//$str = strip_tags(htmlspecialchars($str));
		return mb_substr($str,0,$len,"utf-8");
	}

	function SortStuScore($table){
		$sqladd = "";
		$where = array();
		$whereflag = false;
		$order = array();
		$orderflag = false;
		if(isset($_GET['xsid']))
		{
			$xsid = $_GET['xsid'];
			$xsid = addslashes($xsid);
			$where[] = "{$table}.user_id like '%{$xsid}%'";
		}
		if(isset($_GET['xsname']))
		{
			$xsname = $_GET['xsname'];
			$xsname = addslashes($xsname);
			$where[] = "{$table}.nick like '%{$xsname}%'";
		}
		if(isset($_GET['sortanum']))
		{
			$sortanum = intval($_GET['sortanum']);
			if($sortanum&1) $order[]="choosesum ASC";
			if($sortanum&2) $order[]="judgesum ASC";
			if($sortanum&4) $order[]="fillsum ASC";
			if($sortanum&8) $order[]="programsum ASC";
			if($sortanum&16) $order[]="score ASC";
		}
		if(isset($_GET['sortdnum']))
		{
			$sortdnum = intval($_GET['sortdnum']);
			if($sortdnum&1) $order[]="choosesum DESC";
			if($sortdnum&2) $order[]="judgesum DESC";
			if($sortdnum&4) $order[]="fillsum DESC";
			if($sortdnum&8) $order[]="programsum DESC";
			if($sortdnum&16) $order[]="score DESC";
		}
		if(!empty($where[0]))
		{
			$where = join(' AND ',$where);
			$where = " WHERE ".$where;
		}
		else
			$where = join('',$where);
		if(!empty($order[0]))
		{
			$order = join(',',$order);
			$order = "ORDER BY ".$order;
		}
		else
			$order = join('',$order);
		$sqladd = $where." ".$order;
		return $sqladd;
	}

	function checkAdmin($val,$creator=null,$priflag=null){
		if($val==5){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']||$priflag!=0))
				return false;
			return true;
		}
		else if($val==4){
			if(!(isset($_SESSION['administrator'])||$creator==$_SESSION['user_id']))
				return false;
			else
				return true;
		}
		else if($val==3){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator'])
				||isset($_SESSION['problem_editor']))){
				return false;
			}
			return true;
		}
		else if($val==2){
			if(!(isset($_SESSION['administrator'])
				||isset($_SESSION['contest_creator']))){
				return false;
			}
			return true;
		}
		else if($val==1){
			if(isset($_SESSION['administrator']))
				return true;
			else
				return false;
		}
	}

	function splitpage($table,$searchsql=""){
		$page = I('get.page',1,'intval');
		$each_page=C('EACH_PAGE');
		$pagenum=C('PAGE_NUM');
		$total = M($table)->where($searchsql)->count();
		$totalpage=ceil($total/$each_page);
		if($totalpage==0)       $totalpage=1;
		$page=$page<1?1:$page;
		$page=$page>$totalpage?$totalpage:$page;

		$offset=($page-1)*$each_page;
		$sqladd="$offset,$each_page";

		$lastpage=$totalpage;
		$prepage=$page-1;
		$nextpage=$page+1;

		$startpage=$page-4;
		$startpage=$startpage<1?1:$startpage;
		$endpage=$startpage+$pagenum-1;
		$endpage=$endpage>$totalpage?$totalpage:$endpage;
		return array('page' => $page,
					'prepage' => $prepage,
					'startpage' => $startpage,
					'endpage' => $endpage,
					'nextpage' => $nextpage,
					'lastpage' => $lastpage,
					'eachpage' => $each_page,
					'sqladd' => $sqladd);
	}

	function showpagelast($pageinfo,$url,$urladd=""){
		foreach ($pageinfo as $key => $value) {
			${$key}=$value;
		}
		echo "<nav>";
		echo "<ul class='pagination'>";
		echo "<li><a href='{$url}?page=1&{$urladd}'>First</a></li>";
		if($page==1)
			echo "<li class='disabled'><a href='javascript:;'>Previous</a></li>";
		else
			echo "<li><a href='{$url}?page=$prepage&{$urladd}'>Previous</a></li>";
		for($i=$startpage;$i<=$endpage;$i++)
		{
			if($i==$page)
				echo "<li class='active'><a href='{$url}?page=$i&{$urladd}'>$i</a></li>";
			else
		  		echo "<li><a href='{$url}?page=$i&{$urladd}'>$i</a></li>";
		}
		if($page==$lastpage)
			echo "<li class='disabled'><a href='javascript:;'>Next</a></li>";
		else
			echo "<li><a href='{$url}?page=$nextpage&{$urladd}'>Next</a></li>";
		echo "<li><a href='{$url}?page=$lastpage&{$urladd}'>Last</a></li>";
		echo "</ul>";
		echo "</nav>";
	}
	function test_input($data){
		$data = trim($data);
  		$data = htmlspecialchars($data);
  		return $data;
	}