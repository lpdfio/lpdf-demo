FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build

WORKDIR /src

COPY dotnet/LpdfDemo.csproj ./
RUN dotnet restore

COPY dotnet/Program.cs ./
RUN dotnet publish -c Release -o /app/publish

# --- runtime image ---
FROM mcr.microsoft.com/dotnet/runtime:8.0

WORKDIR /app

COPY --from=build /app/publish ./
COPY examples/ /examples/

# Usage (from the repo root):
#   docker build -f dotnet/Dockerfile -t lpdf-dotnet-demo .
#   docker run --rm lpdf-dotnet-demo


ENTRYPOINT ["dotnet", "LpdfDemo.dll"]
