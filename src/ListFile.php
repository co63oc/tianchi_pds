<?php
namespace AlibabaCloud\SDK\Sample;
use AlibabaCloud\SDK\Sample\Common;

class ListFile {

    /**
     * @param string[] $args
     * @return void
     */
	public static function main($args){
		self::show_info("root", "root");
	}

	public static function show_info($file_id, $name) {
		$list1 = self::list_file($file_id, $name);
		//echo $list1['next_marker'] . "\n";
		foreach($list1['items'] as $v) {
			if ($v['type'] == 'folder') {
				self::show_info($v['file_id'], $name . "_" . $v['name']);
			}
		}
	}

	public static function list_file($file_id, $name = 'root') {
		$ret = Common::ListFile([
				"parent_file_id" => $file_id,
				"drive_id" => Common::get_drive_id(),
				"limit" => 200
		]);

		$ret_data = json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
		file_put_contents("drive/" . $name . ".json", $ret_data);
		return json_decode($ret_data, true);
	}
}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
    require_once $path;
}
ListFile::main(array_slice($argv, 1));
