FROM nginx:1.25-alpine

ENV TZ=${TZ:-UTC}
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk add --update --no-cache tzdata

COPY ./default.conf /etc/nginx/conf.d/default.conf
