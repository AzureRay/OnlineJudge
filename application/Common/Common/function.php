<?php
function sqlInjectionFilter() {
	array_walk($_GET,	function(&$v, $k) { $v = sqlFilter($v); });
	array_walk($_POST,	function(&$v, $k) { $v = sqlFilter($v); });
}

function sqlFilter() {
	$filter = '/mysql[\s|\`]*\.[\s|\`]*(columns_priv|proc|tables_priv|user)|(or|and)\s*\d\s*=\s*\d|drop\s+table|select.*(from|load_file).*\)|insert\s+into\s|delete\s+from\s|truncate\s+\w+($|\s)|UNION\s+SELECT/i';
	$value = preg_replace($filter, ' ', $value);
	return $value;
}

function dbg() {
	if (C('ISDEBUG')) {
		if (func_num_args() === 0) {
			return;
		}
		$vars = func_get_args();
		var_dump($vars);
	}
}