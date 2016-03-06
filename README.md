# Coding.net爬虫
> [Coding.net](https://coding.net/) 是一个面向开发者的云端开发平台,提供 git代码托管，免费的运行演示空间，代码质量分析，在线Web IDE，项目管理，开发协作，悬赏众包，冒泡社交等功能。 为开发者提供技术讨论和开发，协作工具， Coding 极速的代码体验，让开发更简单。

Just for fun and I love coding.net
一个抓取Coding.net用户数据的应用

## 运行环境
- linux(例:Ubuntu 14.04) cli
- PHP version >= 5.5.30
- Mysql version >= 5.6.27
- redis version >= 3.0.5
- pcntl 扩展
- curl 扩展
- pdo 扩展
- redis 扩展

## 使用方法
1. 创建数据库coding_spider，创建数据表user及user_tag；建表文件在 ./sql/user.sql和./sql/user_tag.sql中
2. 开启php,mysql,redis服务
3. 在命令行(cli)模式下运行index.php和get_user_tag.php，index.php是爬虫程序，get_user_tag.php是抓取所有用户标签程序

## 查看统计数据
运行result/chart.php可以看到类似的数据统计图
 ![图片](https://dn-coding-net-production-pp.qbox.me/8444a3cd-f7e0-4746-b120-9800b3e5996b.png) 