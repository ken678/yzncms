# README

本文档描述用 docker-compose 部署 MySQL + Nginx + yzncms 的步骤。

## 部署步骤

1. 准备一台 Linux 主机，安装 git、docker、docker-compose 。例如 CentOS 主机可以执行以下安装命令：
    ```sh
    sudo su -
    yum install -y epel-release git yum-utils python3 python3-pip
    yum-config-manager --add-repo https://download.docker.com/linux/centos/docker-ce.repo
    yum install -y docker-ce
    systemctl start docker
    systemctl enable docker
    
    pip3 install --upgrade pip
    pip3 install docker-compose
    ```

2. 执行以下命令，开始部署：
    ```sh
    # 拉取代码
    mkdir yzncms
    cd yzncms
    git clone https://github.com/ken678/yzncms.git

    # 修改配置文件
    mv yzncms/etc/docker-compose/* . 
    sed -i "s,\('hostname'\s* => \)'127.0.0.1',\1'mysql',g"   yzncms/config/database.php

    # 调整文件权限
    mkdir -p mysql/data
    chown -R 999 mysql
    chown -R 1 yzncms

    # 启动服务
    docker-compose up -d

    # 导入sql文件
    sed -i '1i SET NAMES utf8; USE yzncms;'  yzncms/yzncms.sql
    docker-compose exec -T  mysql mysql -u root --password=root < yzncms/yzncms.sql
    ```

3. 部署之后默认监听 80 端口，请访问网站 `<host>/admin/index/login.html` 。默认登录账号、密码为 admin、admin 。
