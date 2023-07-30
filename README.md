分数7.4左右实现

使用步骤：

1. 保存空间图片到当前空间，使用网页开发工具查看请求中获取的drive\_id值, 比赛后台页面查看 token 值
1. cd src 进入src目录，修改Common.php中 <token>, <drive_id> 为获取的值
1. 运行 php GetFile.php 获取目录列表<br />
   运行 php ListFile.php 获取文件列表, 返回数据保存到drive目录
1. 运行 php CreateSimilarImageClusterTask.php 创建相似图片过滤任务, 记录返回task\_id
1. 修改 SearchSimilarImageClusters.php 中<task_id>为获取的值<br />
   运行 php SearchSimilarImageClusters.php 获取相似图片数据
1. 运行 php SortFile.php 按算法排序，过滤重复图片，生成图片列表json文件
1. 运行 php CreateCustomizedStory.php 创建结果文件

实现内容：
1. 按tag分类图片，每个tag中选择分数最高图片
1. 选择图片使用API过滤相似图片，按分数排序, 调整过滤范围选择图片
