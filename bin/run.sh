#!/bin/bash
docker run -it --rm --name symfony-pocs-login -p 8000:8000 -w /app grader:latest bin/console app:test -vvv