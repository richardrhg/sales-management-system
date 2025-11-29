# 員工銷售管理系統 Dockerfile
# Employee Sales Management System Dockerfile
# For Azure Web App for Containers deployment

FROM php:8.2-apache

# 安裝系統依賴（mbstring 需要 oniguruma）
RUN apt-get update && apt-get install -y \
    libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# 安裝 PHP 擴展
RUN docker-php-ext-install pdo pdo_mysql mbstring

# 設定 PHP 預設字符編碼
RUN echo "default_charset = \"UTF-8\"" >> /usr/local/etc/php/conf.d/docker-php.ini

# 啟用 Apache mod_rewrite
RUN a2enmod rewrite

# 設定 Apache 文件根目錄
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# 更新 Apache 設定
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 設定 Apache AllowOverride
RUN echo '<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/docker-php.conf

RUN a2enconf docker-php

# 複製應用程式檔案
COPY . /var/www/html/

# 設定權限
RUN chown -R www-data:www-data /var/www/html

# 設定工作目錄
WORKDIR /var/www/html

# 暴露 80 port
EXPOSE 80

# 健康檢查
HEALTHCHECK --interval=30s --timeout=3s \
    CMD curl -f http://localhost/ || exit 1

# 啟動 Apache
CMD ["apache2-foreground"]
