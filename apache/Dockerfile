FROM shinsenter/phpfpm-apache:php8.4

RUN touch /etc/apache2/conf-available/charset.conf
RUN echo "AddDefaultCharset UTF-8" >> /etc/apache2/conf-available/charset.conf
RUN a2enconf charset

# This section is from https://github.com/Namoshek/docker-php-mssql
RUN apt-get update \
    && apt-get install -y apt-transport-https curl gnupg2 libpng-dev libzip-dev libbz2-dev zlib1g-dev nano unzip \
    && apt-get clean \
	&& rm -rf /var/lib/apt/lists/*

ENV ACCEPT_EULA=Y
RUN curl -sSL -O https://packages.microsoft.com/config/debian/$(grep VERSION_ID /etc/os-release | cut -d '"' -f 2 | cut -d '.' -f 1)/packages-microsoft-prod.deb \
    && dpkg -i packages-microsoft-prod.deb \
    && rm packages-microsoft-prod.deb \
    && apt-get update \
    && apt-get install -y msodbcsql18 mssql-tools18 unixodbc-dev

# Pre-installed PHP Extensions:
# apcu bcmath calendar exif gd gettext igbinary intl msgpack mysqli opcache pcntl pdo_mysql pdo_pgsql redis sodium uuid yaml zip
RUN phpaddmod bcmath ds pdo_sqlsrv sqlsrv bz2 mbstring
