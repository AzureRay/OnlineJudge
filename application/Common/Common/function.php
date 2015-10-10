<?php

	function sqlInjectionFilter() {
		array_walk($_GET,	function(&$v) { $v = sqlFilter($v); });
		array_walk($_POST,	function(&$v) { $v = sqlFilter($v); });
	}

	function sqlFilter($value) {
		$filter = '/mysql[\s|\`]*\.[\s|\`]*(columns_priv|proc|tables_priv|user)|(or|and)\s*\d\s*=\s*\d|drop\s+table|select.*(from|load_file).*|insert\s+into\s|delete\s+from\s|truncate\s+\w+($|\s)|UNION\s+SELECT/i';
		$value = preg_replace($filter, ' ', $value);
		return $value;
	}


	function pwCheck($password,$saved) {
			if (isOldPW($saved)){
				$mpw = md5($password);
				if ($mpw==$saved) return True;
				else return False;
			}
			$svd=base64_decode($saved);
			$salt=substr($svd,20);
			$hash = base64_encode( sha1(md5($password) . $salt, true) . $salt );
			if (strcmp($hash,$saved)==0) return True;
			else return False;
	}

	function dbg($vars) {
		if (C('ISDEBUG')) {
			var_dump($vars);
			echo "<br/>";
		}
	}

	function ddbg($vars) {
		if (C('ISDEBUG')) {
			var_dump($vars);
			echo "<br/>";
			exit;
		}
	}
