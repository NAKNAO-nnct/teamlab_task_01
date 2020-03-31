FROM gradle:jdk8-alpine as builder

ADD --chown=gradle:gradle build.gradle .
ADD --chown=gradle:gradle src src
RUN gradle installDist


FROM java:8-jre-alpine as runner

RUN addgroup -S -g 1000 app \
    && adduser -D -S -G app -u 1000 -s /bin/ash app
USER app
WORKDIR /home/app
COPY --from=builder --chown=app:app /home/gradle/build/install .
CMD StatisticsApplication/bin/StatisticsApplication