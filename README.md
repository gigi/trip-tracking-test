# Trips tracking test projects

## Requirements
- [Docker Compose](https://docs.docker.com/compose/install/)

## Run

To run local php web server execute:
```
make start
```
OpenApi docs will be accessible here [http://127.0.0.1:8000/api/doc](http://127.0.0.1:8000/api/doc)

Use for development purposes only.

## QA

### Run quality checkers
```
make qa
```

### Run tests
```
make tests
```

### Countries import

Asia and Europe pre-configured script
```
make countries-sync
```
or
```
bin/console countries:sync asia europe
```

## Etc
To see all available command run:
```
make
```

## TODO
- [ ] Make prod ready docker image
- [ ] Totally remove database knowledge from domain entities (e.g. use uuid)
- [ ] Remove all framework-related annotations from domain entities (e.g. use uuid)
