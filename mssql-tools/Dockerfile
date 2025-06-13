FROM alpine

USER root

RUN set -x \
  && tempDir="$(mktemp -d)" \
  && chown nobody:nobody $tempDir \
  && cd $tempDir \
  && wget https://download.microsoft.com/download/fae28b9a-d880-42fd-9b98-d779f0fdd77f/msodbcsql18_18.5.1.1-1_amd64.apk \
  && wget https://download.microsoft.com/download/7/6/d/76de322a-d860-4894-9945-f0cc5d6a45f8/mssql-tools18_18.4.1.1-1_amd64.apk \
  && apk add --allow-untrusted msodbcsql18_18.5.1.1-1_amd64.apk \
  && apk add --allow-untrusted mssql-tools18_18.4.1.1-1_amd64.apk \
  && rm -rf $tempDir \
  && rm -rf /var/cache/apk/*
