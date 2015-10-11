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

	function isValidStringLength($str, $minLength = -1, $maxLength = -1) {
		$len = strlen($str);
		$isValid = true;
		if ($minLength != -1 && $len < $minLength) {
			$isValid = false;
		}
		if ($maxLength != -1 && $len > $maxLength) {
			$isValid = false;
		}
		return $isValid;
	}

	function isValidEmail($str) {
		$moder = "/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/";
		if(preg_match($moder, $str)) {
			return true;
		} else {
			return false;
		}
	}

	function errorReturn($code, $msg = '') {
		$return = array();
		$return['code'] = $code;
		$return['result'] = $msg;
		$result = json_encode($return);
		header('inner-size: '. strlen($result));
		echo $result;
		exit;
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
