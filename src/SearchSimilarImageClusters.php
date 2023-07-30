<?php
namespace AlibabaCloud\SDK\Sample;
use AlibabaCloud\SDK\Sample\Common;

class ListFile {

    /**
     * @param string[] $args
     * @return void
     */
	public static function main($args){
        $ret = self::create_task();
        //print_r($ret);
	}

    public static function create_task() {
        $next_marker = "";
        $ret = Common::SearchSimilarImageClusters([
                "drive_id" => Common::get_drive_id(),
                "task_id" => "<task_id>",
                "limit" => 100,
                "next_marker" => $next_marker
        ]);

        $ret_data = json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents("drive/" . "SearchSimilarImageClusters" . "_" . $next_marker . ".json", $ret_data);
        return json_decode($ret_data, true);
    }
}
$path = __DIR__ . \DIRECTORY_SEPARATOR . '..' . \DIRECTORY_SEPARATOR . 'vendor' . \DIRECTORY_SEPARATOR . 'autoload.php';
if (file_exists($path)) {
    require_once $path;
}
ListFile::main(array_slice($argv, 1));
