#!/bin/bash
docker run -it --rm --name symfony-pocs-login -p 8000:8000 -v $(pwd)/src:/app -w /app grader:latest bash