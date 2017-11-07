<?php
$api_web_template = <<<API_WEB
<?php
// error_reporting(0);
require_once ("../../../init.php");

\$draw         = \$_GET["draw"];
\$page         = \$_GET["page"];
\$page_size    = \$_GET["pageSize"];
\$query        = \$_GET["query"];
\$columns      = \$_GET["columns"];
\$where_clause = "";
\$orderDes     = "$realId desc";

if ( !empty(\$query) ) {
  \$where_clause = "(";
  \$search_atom  = explode(" ", trim(\$query));
  array_walk(\$search_atom, function(&\$value, \$key) {
    \$value = " ( $classNameField LIKE '%" . \$value . "%' ) ";
  });
  \$where_clause .= implode(" and ", \$search_atom);
  \$where_clause .= ")";
}

foreach (\$columns as \$key => \$column) {
  \$column_search_value = \$column["search"]["value"];
  if ( \$column_search_value != "" ) {
    if ( !empty(\$where_clause) ) {
      \$where_clause .= " and ";
    }
    \$where_clause .= " " . \$column["data"] . "='" . \$column_search_value . "' ";
  }
}

\$page{$classname}s = {$classname}::queryPageByPageNo( \$page, \$where_clause, \$page_size, \$orderDes );
\$data = \$page{$classname}s["data"];
if (\$data){
  foreach (\$data as \$key => \${$instancename}) {
$editApiRela
$editApiImg
  }
}
\$recordsFiltered = \$page{$classname}s["count"];
\$recordsTotal    = {$classname}::count();
\$result = array(
  'data' => \$data,
  'draw' => \$draw,
  'recordsFiltered' => \$recordsFiltered,
  'recordsTotal' => \$recordsTotal
);

//调试使用的信息
\$result["debug"] = array(
  'param' => array(
    'columns' => \$columns
  ),
  'where' => \$where_clause
);
echo json_encode(\$result);
?>
API_WEB;

$select_web_template = <<<SELECT_WEB
<?php
// error_reporting(0);
require_once ("../../../init.php");

\$query        = \$_GET["term"];
\$where_clause = "";
if (!empty(\$query)){
  \$where_clause  = "(";
  \$search_atom = explode(" ", trim(\$query));
  array_walk(\$search_atom, function(&\$value, \$key){
    \$value = " ( title LIKE '%" . \$value . "%' ) ";
  });
  \$where_clause .= implode(" and ", \$search_atom);
  \$where_clause .= ")";
}
\$page{$classname_rela} = {$classname_rela}::get(\$where_clause);
\$data     = array();
if (\$page{$classname_rela}){
  foreach (\$page{$classname_rela} as \$key => \${$instancename_rela}) {
    \${$instancename_rela}v         = array();
    \${$instancename_rela}v["id"]   = \${$instancename_rela}->{$realId_m2m};
    \${$instancename_rela}v["text"] = \${$instancename_rela}->title;
    \$data[]        = \${$instancename_rela}v;
  }
}
\$result   = array(
  'code' => 1,
  'description' => "",
  'data' => \$data
);

//调试使用的信息
\$result["debug"] = array(
  'param' => array(
    'term' => \$search
  ),
  'where' => \$where_clause
);
echo json_encode(\$result);

SELECT_WEB;

$edit_sub_json_template = <<<EDIT_JSON
{
    "code": 1,
    "description": "",
    "data": [
$edit_json_enums
    ]
}
EDIT_JSON;
?>
