FROM centos:7

RUN yum -y update
RUN yum -y install httpd httpd-tools mod_ssl openssl expect net-tools telnet vim curl

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh http://rpms.remirepo.net/enterprise/remi-release-7.rpm

RUN yum --enablerepo=remi-php74 -y install php php-bcmath php-cli php-common php-gd php-intl php-ldap php-mbstring \
    php-mysqlnd php-pear php-soap php-xml php-xmlrpc php-zip php-pdo

RUN sed -E -i -e '/<Directory "\/var\/www\/html">/,/<\/Directory>/s/AllowOverride None/AllowOverride All/' /etc/httpd/conf/httpd.conf
RUN sed -E -i -e 's/DirectoryIndex (.*)$/DirectoryIndex index.php \1/g' /etc/httpd/conf/httpd.conf

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php

#RUN curl -sL https://rpm.nodesource.com/setup_16.x | bash - && \
#    yum install nodejs -y

RUN yum clean all

EXPOSE 80

WORKDIR /var/www/html

CMD ["/usr/sbin/httpd","-D","FOREGROUND"]
