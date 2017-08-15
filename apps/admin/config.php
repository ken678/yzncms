<?php
//后台配置文件
return [
    //数据库导出配置参数
	'data_backup_path' => './Data/Backup/', //数据库备份根路径 路径必须以 / 结尾
	'data_backup_part_size' => 20971520, //数据库备份卷大小 该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
	'data_backup_compress' => 1, //0:不压缩 1:启用压缩 压缩备份文件需要PHP环境支持gzopen,gzwrite函数
	'data_backup_compress_level'=> 9, //1:普通 4:一般 9:最高 数据库备份文件的压缩级别，该配置在开启压缩时生效
];