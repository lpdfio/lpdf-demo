FROM maven:3-eclipse-temurin-17 AS build

WORKDIR /src

COPY java/pom.xml ./
RUN mvn -B -ntp dependency:go-offline

COPY java/src ./src
RUN mvn -B -ntp package

# --- runtime image ---
FROM eclipse-temurin:17-jre

WORKDIR /demo

COPY --from=build /src/target/lpdf-java-demo.jar ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f java/Dockerfile -t lpdf-java-demo .
#   docker run --rm lpdf-java-demo


ENTRYPOINT ["java", "-jar", "lpdf-java-demo.jar"]
