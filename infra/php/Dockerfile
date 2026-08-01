# PHP 8.4の公式イメージをベースに使用（PHP-FPM）
FROM php:8.4-fpm-alpine

# WSL2におけるユーザー権限の問題を解決
# ホストOSの UID/GID と一致させるためのビルド引数
ARG GID=1000
ARG UID=1000

# システムパッケージの更新と、必要な依存関係のインストール
RUN apk update && apk add --no-cache \
    git \
    curl \
    # Laravelに必要な拡張機能をビルドするための依存パッケージ
    libxml2-dev \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    $PHPIZE_DEPS \
    # データベース関連
    mysql-client \
    # Node.js
    nodejs \
    npm \
    # よく使われるユーティリティ
    bash

# Laravelに必要なPHP拡張機能の有効化
# pdo_mysql: データベース接続
# opcache: パフォーマンス向上
# bcmath, exif, pcntl, mbstring, gd: Laravelや画像処理などで一般的に必要
RUN docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    opcache \
    bcmath \
    exif \
    pcntl \
    mbstring \
    gd \
    zip

# Composerのインストール
# 最新のComposerイメージから実行ファイルをコピー
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションの作業ディレクトリを設定
# WORKDIR /var/www/html/src
WORKDIR /var/www/src

# ホストOSのユーザーとsrcコンテナのユーザーが一致しないために
# srcのアプリケーション内のファイルを編集できない、もしくはコンテナ内のアプリがアクセスできない問題を解決
# 新規にappgroupとappuserを作成し、ホストOSのユーザーID=1000と一致させる
RUN addgroup -g ${GID} appgroup && \
    adduser -u ${UID} -G appgroup -s /bin/sh -D appuser

# srcコンテナのデフォルト実行ユーザーをappuserに変更してまう場合
USER appuser

# Laravelをインストール後に以下をsrcコンテナ内で実行する、外部から実行しても良い
# chown -R appuser:appgroup /var/www/src && \
# chmod -R 775 /var/www/src/storage /var/www/src/bootstrap/cache
