<?php

// This file is auto-generated, don't edit it. Thanks.
namespace AlibabaCloud\SDK\Sample;

use AlibabaCloud\SDK\Sample\Common;

class GetFile {

    /**
     * @param string[] $args
     * @return void
     */
	public static function main($args){
        $map1 = Common::GetMap();
		foreach ($map1 as $name => $name2) {
			$dir = "drive/" . $name;
			if (!file_exists($dir)) {
				mkdir($dir, 0777, true);
			}
			$ret = self::create("drive/root_Stories_${name}.json", $name2, $name);
		}
	}

	public static function create($path, $name2="", $name="") {
		$data = json_decode(file_get_contents($path), true);

		$files = [];
		foreach ($data['items'] as $v) {
			$files[] = [
				'file_id' => $v['file_id'],
				'revision_id' => $v['revision_id'],
				'name' => $v['name'],
			];
		}
		foreach ($files as $v) {
			$request = [
				"drive_id" => Common::get_drive_id(),
				"file_id" => $v['file_id']
			];
			$ret_data = Common::GetFile($request);

			$ret_json = json_encode($ret_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
			$file1 = "drive/" . $name . "/" . $v['name'] . ".json";
			echo $file1 . "\n";
			file_put_contents($file1, $ret_json);
		}
	}
}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
    require_once $path;
}
GetFile::main(array_slice($argv, 1));
