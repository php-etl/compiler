#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

name=$1

merge_plugin "$name"