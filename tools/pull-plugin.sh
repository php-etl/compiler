#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

name=$1

pull_plugin "$name"