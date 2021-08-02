#!/usr/bin/env bash

PATH=$(dirname "$0")
DOCKER_IMAGE_TAG='notices'

/usr/local/bin/docker build -t ${DOCKER_IMAGE_TAG} "${PATH}"