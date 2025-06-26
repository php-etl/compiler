#!/bin/bash

path=$(dirname $0)

. "$path/_functions.sh"

push "satellite" "compiler/"

push_plugin akeneo
push_plugin csv
push_plugin fast-map
push_plugin json
push_plugin log
push_plugin prestashop
push_plugin spreadsheet
push_plugin sql
push_plugin sylius