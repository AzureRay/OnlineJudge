<?php

function makesx($myarr, $start, $finish, $stunum) {
    $num = $finish - $start + 1;
    $fac = array(1, 1, 2, 6, 24, 120, 720, 5040, 40320, 362880, 3628800, 39916800);
    $rightnum = $stunum % $fac[$num];
    $tmp = array();
    for ($i = 1; $i <= $num; $i++)
        $tmp[] = $i;
    for ($i = 1; $i <= $num; $i++) {
        $div = floor($rightnum / $fac[$num - $i]);
        $rightnum = $rightnum % $fac[$num - $i];
        $myarr[$start + $i - 1] = $tmp[$div] - 1 + $start;
        array_splice($tmp, $div, 1);
    }
    unset($tmp);
    unset($fac);
    return $myarr;
}

function checkAdmin($val, $creator = null, $priflag = null) {
    if ($val == 5) {
        if (!(isset($_SESSION['administrator']) || $creator == $_SESSION['user_id'] || $priflag != 0))
            return false;
        return true;
    } else if ($val == 4) {
        if (!(isset($_SESSION['administrator']) || $creator == $_SESSION['user_id']))
            return false;
        else
            return true;
    } else if ($val == 3) {
        if (!(isset($_SESSION['administrator'])
            || isset($_SESSION['contest_creator'])
            || isset($_SESSION['problem_editor']))
        ) {
            return false;
        }
        return true;
    } else if ($val == 2) {
        if (!(isset($_SESSION['administrator'])
            || isset($_SESSION['contest_creator']))
        ) {
            return false;
        }
        return true;
    } else if ($val == 1) {
        if (isset($_SESSION['administrator']))
            return true;
        else
            return false;
    }
}

function splitpage($table, $searchsql = "") {
    $page = I('get.page', 1, 'intval');
    $each_page = C('EACH_PAGE');
    $pagenum = C('PAGE_NUM');
    $total = M($table)->where($searchsql)->count();
    $totalpage = ceil($total / $each_page);
    if ($totalpage == 0) $totalpage = 1;
    $page = $page < 1 ? 1 : $page;
    $page = $page > $totalpage ? $totalpage : $page;

    $offset = ($page - 1) * $each_page;
    $sqladd = "$offset,$each_page";

    $lastpage = $totalpage;
    $prepage = $page - 1;
    $nextpage = $page + 1;

    $startpage = $page - 4;
    $startpage = $startpage < 1 ? 1 : $startpage;
    $endpage = $startpage + $pagenum - 1;
    $endpage = $endpage > $totalpage ? $totalpage : $endpage;
    return array('page' => $page,
        'prepage' => $prepage,
        'startpage' => $startpage,
        'endpage' => $endpage,
        'nextpage' => $nextpage,
        'lastpage' => $lastpage,
        'eachpage' => $each_page,
        'sqladd' => $sqladd);
}

function showpagelast($pageinfo, $url, $urladd = "") {
    foreach ($pageinfo as $key => $value) {
        ${$key} = $value;
    }
    echo "<nav>";
    echo "<ul class='pagination'>";
    echo "<li><a href='{$url}?page=1&{$urladd}'>First</a></li>";
    if ($page == 1)
        echo "<li class='disabled'><a href='javascript:;'>Previous</a></li>";
    else
        echo "<li><a href='{$url}?page=$prepage&{$urladd}'>Previous</a></li>";
    for ($i = $startpage; $i <= $endpage; $i++) {
        if ($i == $page)
            echo "<li class='active'><a href='{$url}?page=$i&{$urladd}'>$i</a></li>";
        else
            echo "<li><a href='{$url}?page=$i&{$urladd}'>$i</a></li>";
    }
    if ($page == $lastpage)
        echo "<li class='disabled'><a href='javascript:;'>Next</a></li>";
    else
        echo "<li><a href='{$url}?page=$nextpage&{$urladd}'>Next</a></li>";
    echo "<li><a href='{$url}?page=$lastpage&{$urladd}'>Last</a></li>";
    echo "</ul>";
    echo "</nav>";
}

function test_input($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>